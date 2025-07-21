FROM node:20.17.0-alpine3.20
WORKDIR /app
RUN corepack enable && corepack prepare yarn@stable --activate
EXPOSE 8080
CMD ["sh", "-c", "tail -f /dev/null"]

