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
# echo "@1204220052 | @1204220007"

# Step 1: Remove stopped containers
echo "🗑️  Removing stopped containers..."
docker container prune -f

# Step 2: Remove unused images
echo "🖼️  Removing unused images..."
docker image prune -a -f

# Step 3: Remove unused volumes
echo "🧱 Removing unused volumes..."
docker volume prune -f

# Step 4: Remove unused networks
echo "🌐 Removing unused networks..."
docker network prune -f

# Step 5: Remove build cache
echo "🧰 Cleaning Docker builder cache..."
docker builder prune -a -f

echo ""
echo "✅ Docker cleanup completed!"
echo ""

# Optional: Show disk usage summary
echo "📊 Docker disk usage after cleanup:"
docker system df

echo ""
