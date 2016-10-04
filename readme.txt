===========
Minimal CMS

Lightweight, component based, extensible, fast, accessible, mobile friendly website, that enables
user to edit content of any database.

There are 3 main components included (content, nodes, images) + login (disabled).
Components could be localised (dictionary is in json format).

Create, Update and Delete methods are not included for demo purpose (depend on database).

There is also a complete minimalistic MVC framework behind. Framework core component is app.
Feel free to build any website on it.

===========
Directory structure:

cfg/ - app config
comps/ - components
 (every component can have its own view, model, css, js, json config etc.)
css/ - css images and other resources
db/ - database (sqlite3)
images/ - uploaded images
views/
 (every view can have its own css, js etc.)
===========

===========
web.config:

web.config is only enabling compression
===========