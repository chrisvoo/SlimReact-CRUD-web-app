rsync -av -e ssh --exclude='api/vendor' api ccastelli@localhost:/var/www/chessable
ssh ccastelli@localhost 'cd /var/www/chessable && composer install'
cd ui
npm install
npm run build
scp -r build/* ccastelli@localhost:/var/www/chessable
