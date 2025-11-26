FROM node:22.16.0
WORKDIR /usr/src/app
COPY package*.json ./
COPY . .
RUN rm -rf node_modules
RUN npm install
EXPOSE 3006
CMD [ "npm", "run",  "dev"]