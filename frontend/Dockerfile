# Stage 1: Build Angular app
FROM node:20-alpine AS builder

# Set working directory
WORKDIR /app

# Install dependencies
COPY package*.json ./
RUN npm install

# Copy source code and build the app
COPY . .
RUN npm run build -- --configuration production

# Stage 2: Serve with Nginx
FROM nginx:stable-alpine AS production

# Copy the compiled app to Nginx public folder
COPY --from=builder /app/dist/hostcontrol/browser /usr/share/nginx/html

# Replace default Nginx config with custom one for SPA
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expose port 80 inside the container
EXPOSE 80

# Start Nginx in foreground
CMD ["nginx", "-g", "daemon off;"]