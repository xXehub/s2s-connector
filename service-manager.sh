#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
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
    echo "1. 🗄️  Database Operations"
    echo "2. 🐰 RabbitMQ Management"
    echo "3. ⚙️  Queue Worker Management"
    echo "4. 🔧 Laravel Optimization"
    echo "5. 📊 System Monitoring"
    echo "6. 🧪 Testing Tools"
    echo "7. 📋 View Logs"
    echo "8. ❌ Exit"
    echo ""
    read -p "Enter your choice [1-8]: " choice
}

database_menu() {
    echo -e "${BLUE}🗄️ Database Operations:${NC}"
    echo "1. Run migrations (all services)"
    echo "2. Seed databases"
    echo "3. Reset databases"
    echo "4. Check database connections"
    echo "5. Back to main menu"
    echo ""
    read -p "Enter your choice [1-5]: " db_choice
    
    case $db_choice in
        1) migrate_databases ;;
        2) seed_databases ;;
        3) reset_databases ;;
        4) check_db_connections ;;
        5) return ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
}

migrate_databases() {
    echo -e "${GREEN}🔄 Running migrations...${NC}"
    echo "User Service:"
    docker compose exec user-service php artisan migrate --force
    echo "Product Service:"
    docker compose exec product-service php artisan migrate --force
    echo "Order Service:"
    docker compose exec order-service php artisan migrate --force
    echo -e "${GREEN}✅ All migrations completed!${NC}"
}

seed_databases() {
    echo -e "${GREEN}🌱 Seeding databases...${NC}"
    echo "User Service:"
    docker compose exec user-service php artisan db:seed --force
    echo "Product Service:"
    docker compose exec product-service php artisan db:seed --force
    echo -e "${GREEN}✅ All databases seeded!${NC}"
}

reset_databases() {
    echo -e "${RED}⚠️ This will reset all databases!${NC}"
    read -p "Are you sure? (y/N): " confirm
    if [[ $confirm == [yY] ]]; then
        echo -e "${YELLOW}Resetting databases...${NC}"
        docker compose exec user-service php artisan migrate:fresh --seed --force
        docker compose exec product-service php artisan migrate:fresh --seed --force
        docker compose exec order-service php artisan migrate:fresh --seed --force
        echo -e "${GREEN}✅ All databases reset!${NC}"
    fi
}

check_db_connections() {
    echo -e "${BLUE}🔍 Checking database connections...${NC}"
    echo "User Service:"
    docker compose exec user-service php artisan tinker --execute="echo 'Connection: ' . (DB::connection()->getPdo() ? 'OK' : 'FAILED') . '\n';"
    echo "Product Service:"
    docker compose exec product-service php artisan tinker --execute="echo 'Connection: ' . (DB::connection()->getPdo() ? 'OK' : 'FAILED') . '\n';"
    echo "Order Service:"
    docker compose exec order-service php artisan tinker --execute="echo 'Connection: ' . (DB::connection()->getPdo() ? 'OK' : 'FAILED') . '\n';"
}

rabbitmq_menu() {
    echo -e "${BLUE}🐰 RabbitMQ Management:${NC}"
    echo "1. Show queue status"
    echo "2. Purge all queues"
    echo "3. Test RabbitMQ connection"
    echo "4. Open RabbitMQ Management UI"
    echo "5. Back to main menu"
    echo ""
    read -p "Enter your choice [1-5]: " rmq_choice
    
    case $rmq_choice in
        1) docker compose exec rabbitmq rabbitmqctl list_queues ;;
        2) docker compose exec rabbitmq rabbitmqctl purge_queue product-stock-update ;;
        3) docker compose exec rabbitmq rabbitmq-diagnostics ping ;;
        4) echo "Opening http://localhost:15672 (admin/admin123)"; open http://localhost:15672 2>/dev/null || echo "Please open http://localhost:15672 manually" ;;
        5) return ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
}

worker_menu() {
    echo -e "${BLUE}⚙️ Queue Worker Management:${NC}"
    echo "1. Start worker"
    echo "2. Stop worker"
    echo "3. Restart worker"
    echo "4. Check worker status"
    echo "5. View worker logs"
    echo "6. Back to main menu"
    echo ""
    read -p "Enter your choice [1-6]: " worker_choice
    
    case $worker_choice in
        1) docker compose restart product-queue-worker; echo -e "${GREEN}✅ Worker started!${NC}" ;;
        2) docker compose stop product-queue-worker; echo -e "${YELLOW}🛑 Worker stopped!${NC}" ;;
        3) docker compose restart product-queue-worker; echo -e "${GREEN}🔄 Worker restarted!${NC}" ;;
        4) docker compose exec product-service ps aux | grep 'queue:work' || echo "No worker running" ;;
        5) docker compose logs -f product-queue-worker ;;
        6) return ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
}

optimization_menu() {
    echo -e "${BLUE}🔧 Laravel Optimization:${NC}"
    echo "1. Clear all caches"
    echo "2. Optimize applications"
    echo "3. Generate autoload files"
    echo "4. Clear and optimize (full)"
    echo "5. Back to main menu"
    echo ""
    read -p "Enter your choice [1-5]: " opt_choice
    
    case $opt_choice in
        1) clear_caches ;;
        2) optimize_apps ;;
        3) generate_autoload ;;
        4) full_optimization ;;
        5) return ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
}

clear_caches() {
    echo -e "${YELLOW}🧹 Clearing caches...${NC}"
    for service in user-service product-service order-service; do
        echo "Clearing cache for $service:"
        docker compose exec $service php artisan cache:clear
        docker compose exec $service php artisan config:clear
        docker compose exec $service php artisan route:clear
        docker compose exec $service php artisan view:clear
    done
    echo -e "${GREEN}✅ All caches cleared!${NC}"
}

optimize_apps() {
    echo -e "${YELLOW}⚡ Optimizing applications...${NC}"
    for service in user-service product-service order-service; do
        echo "Optimizing $service:"
        docker compose exec $service php artisan optimize
    done
    echo -e "${GREEN}✅ All applications optimized!${NC}"
}

generate_autoload() {
    echo -e "${YELLOW}📦 Generating autoload files...${NC}"
    for service in user-service product-service order-service; do
        echo "Generating autoload for $service:"
        docker compose exec $service composer dump-autoload
    done
    echo -e "${GREEN}✅ Autoload files generated!${NC}"
}

full_optimization() {
    echo -e "${YELLOW}🚀 Full optimization...${NC}"
    clear_caches
    generate_autoload
    optimize_apps
    echo -e "${GREEN}✅ Full optimization completed!${NC}"
}

monitoring_menu() {
    echo -e "${BLUE}📊 System Monitoring:${NC}"
    echo "1. Service status"
    echo "2. Database status"
    echo "3. Queue status"
    echo "4. Recent orders"
    echo "5. Product stock levels"
    echo "6. System overview"
    echo "7. Back to main menu"
    echo ""
    read -p "Enter your choice [1-7]: " mon_choice
    
    case $mon_choice in
        1) docker compose ps ;;
        2) check_db_connections ;;
        3) docker compose exec rabbitmq rabbitmqctl list_queues ;;
        4) docker compose exec order-service php artisan tinker --execute="App\Models\Order::latest()->take(5)->get(['id','product_name','quantity','total_price','created_at'])->each(function(\$o){echo 'Order #'.\$o->id.' | '.\$o->product_name.' | Qty: '.\$o->quantity.' | '.\$o->created_at.PHP_EOL;});" ;;
        5) docker compose exec product-service php artisan tinker --execute="App\Models\Product::all(['id','name','stock'])->each(function(\$p){echo 'ID: '.\$p->id.' | '.\$p->name.' | Stock: '.\$p->stock.PHP_EOL;});" ;;
        6) system_overview ;;
        7) return ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
}

system_overview() {
    echo -e "${PURPLE}🔍 SYSTEM OVERVIEW${NC}"
    echo "=================="
    echo ""
    echo -e "${BLUE}🐳 Docker Services:${NC}"
    docker compose ps
    echo ""
    echo -e "${BLUE}🐰 RabbitMQ Queues:${NC}"
    docker compose exec rabbitmq rabbitmqctl list_queues 2>/dev/null || echo "RabbitMQ not accessible"
    echo ""
    echo -e "${BLUE}⚙️ Queue Worker:${NC}"
    docker compose exec product-service ps aux | grep 'queue:work' || echo "No worker running"
    echo ""
    echo -e "${BLUE}🌐 Service URLs:${NC}"
    echo "Frontend: http://localhost:8000"
    echo "Order API: http://localhost:8002/api/orders"
    echo "Product API: http://localhost:8003/api/products"
    echo "User API: http://localhost:8001/api/users"
    echo "RabbitMQ: http://localhost:15672"
    echo "phpMyAdmin: http://localhost:8080"
}

testing_menu() {
    echo -e "${BLUE}🧪 Testing Tools:${NC}"
    echo "1. Test order creation"
    echo "2. Test stock reduction"
    echo "3. Test all APIs"
    echo "4. Load test (10 orders)"
    echo "5. Back to main menu"
    echo ""
    read -p "Enter your choice [1-5]: " test_choice
    
    case $test_choice in
        1) test_order_creation ;;
        2) test_stock_reduction ;;
        3) test_all_apis ;;
        4) load_test ;;
        5) return ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
}

test_order_creation() {
    echo -e "${YELLOW}🧪 Testing order creation...${NC}"
    curl -X POST http://localhost:8002/api/orders \
      -H "Content-Type: application/json" \
      -d '{"user_id": 1, "product_id": 1, "product_quantity": 1}' \
      -s | python3 -m json.tool 2>/dev/null || echo "Order creation test completed (install python3 for pretty JSON)"
}

test_stock_reduction() {
    echo -e "${YELLOW}🧪 Testing stock reduction...${NC}"
    echo "Stock before:"
    docker compose exec product-service php artisan tinker --execute="echo 'Stock: ' . App\Models\Product::find(1)->stock . PHP_EOL;"
    
    echo "Creating order..."
    curl -X POST http://localhost:8002/api/orders \
      -H "Content-Type: application/json" \
      -d '{"user_id": 1, "product_id": 1, "product_quantity": 1}' \
      -s > /dev/null
    
    echo "Waiting 5 seconds for processing..."
    sleep 5
    
    echo "Stock after:"
    docker compose exec product-service php artisan tinker --execute="echo 'Stock: ' . App\Models\Product::find(1)->stock . PHP_EOL;"
}

test_all_apis() {
    echo -e "${YELLOW}🧪 Testing all APIs...${NC}"
    echo "User API:"
    curl -s http://localhost:8001/api/users | head -c 100
    echo -e "\n\nProduct API:"
    curl -s http://localhost:8003/api/products | head -c 100
    echo -e "\n\nOrder API:"
    curl -s http://localhost:8002/api/orders | head -c 100
    echo -e "\n\n✅ API tests completed!"
}

load_test() {
    echo -e "${YELLOW}🧪 Running load test (10 orders)...${NC}"
    for i in {1..10}; do
        echo "Creating order $i..."
        curl -X POST http://localhost:8002/api/orders \
          -H "Content-Type: application/json" \
          -d '{"user_id": 1, "product_id": 1, "product_quantity": 1}' \
          -s > /dev/null
        sleep 1
    done
    echo -e "${GREEN}✅ Load test completed!${NC}"
}

logs_menu() {
    echo -e "${BLUE}📋 View Logs:${NC}"
    echo "1. All services"
    echo "2. Order service"
    echo "3. Product service"
    echo "4. Queue worker"
    echo "5. RabbitMQ"
    echo "6. Laravel logs (Product)"
    echo "7. Laravel logs (Order)"
    echo "8. Back to main menu"
    echo ""
    read -p "Enter your choice [1-8]: " log_choice
    
    case $log_choice in
        1) docker compose logs -f ;;
        2) docker compose logs -f order-service ;;
        3) docker compose logs -f product-service ;;
        4) docker compose logs -f product-queue-worker ;;
        5) docker compose logs -f rabbitmq ;;
        6) docker compose exec product-service tail -f storage/logs/laravel.log ;;
        7) docker compose exec order-service tail -f storage/logs/laravel.log ;;
        8) return ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
}

# Main loop
while true; do
    show_menu
    case $choice in
        1) database_menu ;;
        2) rabbitmq_menu ;;
        3) worker_menu ;;
        4) optimization_menu ;;
        5) monitoring_menu ;;
        6) testing_menu ;;
        7) logs_menu ;;
        8) echo -e "${GREEN}👋 Goodbye!${NC}"; exit 0 ;;
        *) echo -e "${RED}❌ Invalid option. Please try again.${NC}" ;;
    esac
    
    echo ""
    read -p "Press Enter to continue..."
done
