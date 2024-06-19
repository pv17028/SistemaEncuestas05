# Usa una imagen base de Node.js
FROM node:14-alpine

# Establece el directorio de trabajo
WORKDIR /app

# Copia el archivo package.json y package-lock.json
COPY package*.json ./

# Instala las dependencias del proyecto
RUN npm install

# Copia el resto de los archivos del proyecto
COPY . .

# Construye la aplicación usando el script "build"
RUN npm run build

# Expone el puerto en el que la aplicación se ejecutará
EXPOSE 4173

# Define el comando por defecto para ejecutar la aplicación
CMD ["npm", "run", "start"]
