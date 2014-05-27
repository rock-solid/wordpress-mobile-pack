# Get the directory that this configuration file exists in
dir = File.dirname(__FILE__)

# Load the sencha-touch framework automatically.
load File.join(dir, '..', '..', 'touch', 'resources', 'themes')

load File.join(dir, '..', 'themes', 'lib')

# Compass configurations
sass_path = dir
css_path = File.join(dir, "..", "css")
fonts_path = File.join(dir, "..", "themes", "fonts")
# http_fonts_dir = File.join( "..", "themes", "fonts")


# Require any additional compass plugins here.
images_dir = File.join(dir, "..", "images")

theme_image = File.join(dir, '..', 'themes', 'lib', 'theme-image.rb')
require theme_image

#output_style = :compressed
#environment = :production

output_style = :expanded
environment = :development
