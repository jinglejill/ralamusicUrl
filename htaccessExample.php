//    RewriteRule (.*)yamaha-test/ https://ralamusic.com/yamaha-abc/ [NC,L]
//    RewriteRule (.*)yamaha-abc/ https://ralamusic.com/yamaha-fg820/ [NC,L]
//
//
//
//    RewriteCond %{HTTP_ACCEPT} image/webp
//    RewriteCond %{REQUEST_FILENAME} -f
//    RewriteCond %{REQUEST_FILENAME}.webp -f
//    RewriteRule ^(.*)\.(jpe?g|png)$ https://ralamusic.com/$1.$2.webp [NC,L]
//
//
//    RewriteCond %{REQUEST_FILENAME}.webp !-f
//    RewriteRule ^(.*)\.(jpe?g|png)$ createWebPOnDemand.php?path=%{SCRIPT_FILENAME}&image=$1.$2 [NC,L]
//
//    <IfModule mod_headers.c>
//    <FilesMatch "(?i)\.(jpe?g|png)$">
//      Header append "Vary" "Accept"
//    </FilesMatch>
//    </IfModule>
//
    
