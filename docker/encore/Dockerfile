FROM node:20.6.0

WORKDIR /var/www

ARG USER_ID

RUN yarn add \
    @symfony/webpack-encore \
    sass-loader \
    node-sass \
    --dev

RUN usermod -u $USER_ID node
USER node