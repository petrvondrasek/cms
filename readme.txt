===========
Minimal CMS



===========
Directory structure:

app/ - application code

root/ - root directory accessible from outside

===========
Requierements:

1. .htaccess with mod rewrite

2. basedir set to /
   - to allow access from root/ to app/ directory

===========
How to run on Apache/Windows:

1. edit C:\Windows\System32\drivers\etc\hosts
 - add something like 127.0.0.1	cms.server
 - then the site will be on http://cms.server

2. On Apache add VirtualHost to httpd.conf

<VirtualHost 127.0.0.1>
  DocumentRoot "C:/server/cms/root"
  ServerName cms.server
</VirtualHost>

Where DocumentRoot points to root/ directory and ServerName is the string in hosts.
===========
