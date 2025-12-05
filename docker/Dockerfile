FROM webdevops/php-nginx:8.2

#Instala Node.js 18 LTS
RUN apt-get update && \
    apt-get install -y curl gnupg && \
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean && rm -rf var/lib/apt/lists/*

#Define o diretório de trabalho
WORKDIR /app