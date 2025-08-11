# Use uma imagem base do Node.js (versão 18, 'alpine' é uma versão leve)
FROM node:18-alpine

# Defina o diretório de trabalho dentro do container
WORKDIR /app

# Copie o package.json e package-lock.json para o container
# Isso otimiza o cache do Docker. O npm install só roda de novo se esses arquivos mudarem.
COPY package*.json ./

# Instale as dependências do projeto
RUN npm install

# Copie todo o resto do código do projeto para o container
COPY . .

# Exponha a porta 5173, que é a porta padrão do Vite
EXPOSE 5173

# O comando que será executado quando o container iniciar
CMD ["npm", "run", "dev"]