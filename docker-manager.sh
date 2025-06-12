#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}
▄▄▄█████▓▓█████  ██▓     ██ ▄█▀ ▒█████   ███▄ ▄███▓
▓  ██▒ ▓▒▓█   ▀ ▓██▒     ██▄█▒ ▒██▒  ██▒▓██▒▀█▀ ██▒
▒ ▓██░ ▒░▒███   ▒██░    ▓███▄░ ▒██░  ██▒▓██    ▓██░
░ ▓██▓ ░ ▒▓█  ▄ ▒██░    ▓██ █▄ ▒██   ██░▒██    ▒██ 
  ▒██▒ ░ ░▒████▒░██████▒▒██▒ █▄░ ████▓▒░▒██▒   ░██▒
  ▒ ░░   ░░ ▒░ ░░ ▒░▓  ░▒ ▒▒ ▓▒░ ▒░▒░▒░ ░ ▒░   ░  ░
    ░     ░ ░  ░░ ░ ▒  ░░ ░▒ ▒░  ░ ▒ ▒░ ░  ░      ░
  ░         ░     ░ ░   ░ ░░ ░ ░ ░ ░ ▒  ░      ░   
            ░  ░    ░  ░░  ░       ░ ░         ░   
 @1204220052, @1204220007, @                                                                                                                                                          
"
# echo "=================="

show_menu() {
    echo ""
    echo -e "${YELLOW}Pilih menu:${NC}"
    echo "1. 🚀 Start all services"
    echo "2. 🛑 Stop all services"
    echo "3. 🗑️  Remove all containers and volumes"
    echo "4. 🔄 Restart all services"
    echo "5. 📊 Show service status"
    echo "6. 📋 Show service logs"
    echo "7. 🔧 Rebuild and restart"
    echo "8. 🧹 Clean unused Docker resources"
    echo "9. ❌ Exit"
    echo ""
    read -p "Enter your choice [1-9]: " choice
}

start_services() {
    echo -e "${GREEN}🚀 Starting all services...${NC}"
    docker compose up -d
    echo -e "${GREEN}✅ All services started!${NC}"
    echo ""
    echo "🌐 Service URLs:"
    echo "Frontend: http://localhost:8000"
    echo "User API: http://localhost:8001/api/users"
    echo "Order API: http://localhost:8002/api/orders"
    echo "Product API: http://localhost:8003/api/products"
    echo "RabbitMQ Management: http://localhost:15672 (admin/admin123)"
    echo "phpMyAdmin: http://localhost:8090"
    echo "Hasura GraphQL Console: http://localhost:8080/console (admin secret: hasura-admin-secret)"
    echo "Hasura GraphQL Endpoint: http://localhost:8080/v1/graphql"
}

stop_services() {
    echo -e "${YELLOW}🛑 Stopping all services...${NC}"
    docker compose down
    echo -e "${GREEN}✅ All services stopped!${NC}"
}

remove_all() {
    echo -e "${RED}🗑️ This will remove all containers, networks, and volumes!${NC}"
    read -p "Are you sure? (y/N): " confirm
    if [[ $confirm == [yY] || $confirm == [yY][eE][sS] ]]; then
        echo -e "${RED}Removing all containers and volumes...${NC}"
        docker compose down -v --remove-orphans
        docker system prune -f
        echo -e "${GREEN}✅ All containers and volumes removed!${NC}"
    else
        echo "Operation cancelled."
    fi
}

restart_services() {
    echo -e "${YELLOW}🔄 Restarting all services...${NC}"
    docker compose restart
    echo -e "${GREEN}✅ All services restarted!${NC}"
}

show_status() {
    echo -e "${BLUE}📊 Service Status:${NC}"
    docker compose ps
}

show_logs() {
    echo -e "${BLUE}📋 Available services:${NC}"
    echo "1. All services"
    echo "2. Frontend"
    echo "3. User Service"
    echo "4. Product Service"
    echo "5. Order Service"
    echo "6. Queue Worker"
    echo "7. RabbitMQ"
    echo "8. Hasura GraphQL Engine"
    echo "9. Hasura Postgres"
    echo "10. Hasura MySQL Connector"
    echo ""
    read -p "Select service to view logs [1-10]: " log_choice
    
    case $log_choice in
        1) docker compose logs -f ;;
        2) docker compose logs -f frontend ;;
        3) docker compose logs -f user-service ;;
        4) docker compose logs -f product-service ;;
        5) docker compose logs -f order-service ;;
        6) docker compose logs -f product-queue-worker ;;
        7) docker compose logs -f rabbitmq ;;
        8) docker compose logs -f hasura ;;
        9) docker compose logs -f hasura-postgres ;;
        10) docker compose logs -f hasura-connector-mysql ;;
        *) echo "Invalid choice" ;;
    esac
}

rebuild_services() {
    set -e

    echo "🐰 Setting up Microservices with RabbitMQ and Hasura GraphQL Integration..."

    # Stop all services
    echo "🛑 Stopping all services..."
    docker compose down

    # Clean up
    echo "🧹 Cleaning up..."
    docker compose down -v
    docker system prune -f

    # Setup Hasura environment file
    echo "📝 Setting up Hasura environment file..."
    if [ ! -f "hasura/.env" ]; then
        cp "hasura/.env.example" "hasura/.env"
        echo "✅ Hasura environment file created"
    fi

    # Start services
    echo "🚀 Starting services..."
    docker compose up -d --build

    # Wait for services
    echo "⏳ Waiting for services to be ready..."
    sleep 30

    # Wait for RabbitMQ
    echo "🐰 Waiting for RabbitMQ..."
    until docker compose exec rabbitmq rabbitmqctl status > /dev/null 2>&1; do
        echo "Waiting for RabbitMQ..."
        sleep 5
    done

    # Setup environment files
    echo "📝 Setting up environment files..."
    for service in user-service order-service product-service; do
        if [ ! -f "backend/$service/.env" ]; then
            cp "backend/$service/.env.example" "backend/$service/.env"
            docker compose exec ${service%-service}-app php artisan key:generate --force
        fi
    done

    if [ ! -f "frontend/.env" ]; then
        cp "frontend/.env.example" "frontend/.env"
        docker compose exec frontend-app php artisan key:generate --force
    fi

    # Run migrations
    echo "🗄️ Running migrations..."
    docker compose exec user-app php artisan migrate --force
    docker compose exec order-app php artisan migrate --force
    docker compose exec product-app php artisan migrate --force
    # docker compose exec frontend-app php artisan migrate --force

    # Install RabbitMQ package
    echo "📦 Installing RabbitMQ packages..."
    # docker compose exec order-app composer require vladimir-yuldashev/laravel-queue-rabbitmq
    # docker compose exec product-app composer require vladimir-yuldashev/laravel-queue-rabbitmq

    # Seed data
    echo "🌱 Seeding data..."
    docker compose exec user-app php artisan db:seed --force
    docker compose exec product-app php artisan db:seed --force

    # Clear cache
    echo "🧹 Clearing cache..."
    docker compose exec order-app php artisan config:clear
    docker compose exec order-app php artisan cache:clear
    docker compose exec product-app php artisan config:clear
    docker compose exec product-app php artisan cache:clear

    # Restart queue worker
    echo "🔄 Starting queue worker..."
    docker compose restart product-queue-worker

    # Wait for Hasura to be ready
    echo "🔄 Waiting for Hasura GraphQL Engine to be ready..."
    sleep 10
    echo "✅ Hasura GraphQL Engine should be ready now"

    echo "✅ Microservices setup with RabbitMQ and Hasura GraphQL completed!"
    echo ""
    echo "🌐 Access URLs:"
    echo "Semua service ready wak!"
    echo "Access points:"
    echo "- Frontend: http://localhost:8000"
    echo "- User Service: http://localhost:8001"
    echo "- Order Service: http://localhost:8002"
    echo "- Product Service: http://localhost:8003"
    echo "- phpMyAdmin: http://localhost:8090"
    echo "- RabbitMQ Management: http://localhost:15672 (admin/admin123)"
    echo "- Hasura GraphQL Console: http://localhost:8080/console (admin secret: hasura-admin-secret)"
    echo "- Hasura GraphQL Endpoint: http://localhost:8080/v1/graphql"
}

clean_docker() {
    echo -e "${YELLOW}🧹 Cleaning unused Docker resources...${NC}"
    docker system prune -f
    docker image prune -f
    docker volume prune -f
    echo -e "${GREEN}✅ Docker cleanup completed!${NC}"
}

# Main loop
while true; do
    show_menu
    case $choice in
        1) start_services ;;
        2) stop_services ;;
        3) remove_all ;;
        4) restart_services ;;
        5) show_status ;;
        6) show_logs ;;
        7) rebuild_services ;;
        8) clean_docker ;;
        9) echo -e "${GREEN}👋 Goodbye!${NC}"; exit 0 ;;
        *) echo -e "${RED}❌ Invalid option. Please try again.${NC}" ;;
    esac
    
    echo ""
    read -p "Press Enter to continue..."
done
