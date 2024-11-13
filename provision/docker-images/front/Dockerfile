# Image
FROM node:18-alpine as build-stage

RUN npm install -g npm@10.2.1
RUN mkdir -p /var/www/app
# Set up work directory
COPY frontend /var/www/front

WORKDIR /var/www/front
RUN npm i

#RUN npm install
# Configure host

#EXPOSE 8080
# Init command
#ENTRYPOINT ["/bin/sh","npm run dev"]
#CMD [ "sh", "-c", "npm run serve"]


