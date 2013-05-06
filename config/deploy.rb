# What is the name of the local application?
set :application, "YOUR_DEPLOYMENT_SERVER_DOMAIN"

# What user is connecting to the remote server?
set :user, "YOUR_DEPLOYMENT_SERVER_USERNAME"

# Where is the local repository?
set :repository,  "file:///ABSOLUTE/PATH/TO/YOUR/PROJECT"

# What is the production server domain?
role :web, "YOUR_DEPLOYMENT_SERVER_DOMAIN"

# What remote directory hosts the production website?
set :deploy_to,   "ABSOLUTE/PATH/TO/YOUR/REMOTE/PROJECT"

# Is sudo required to manipulate files on the remote server?
set :use_sudo, false
 
# What version control solution does the project use?
set :scm,        :git
set :branch,     'master'

# How are the project files being transferred?
# Consider looking at :remote_cache too!
set :deploy_via, :copy

# Maintain a local repository cache. This speeds up the :copy process
set :copy_cache, true

# Ignore any local files?
set :copy_exclude, %w(.git)
 
# This task will symlink the proper .htaccess file to ensure the 
# production server's APPLICATION_ENV variable is set to production
task :create_symlinks, :roles => :web do
  run "rm #{current_release}/public/.htaccess"
  run "ln -s #{current_release}/shared/.htaccess #{current_release}/public/.htaccess"
  run "ln -s /home/wjgilmorecom/libraries/Zend #{current_release}/library/Zend"
end
 
# After deployment has successfully completed
# create the .htaccess symlink
after "deploy:finalize_update", :create_symlinks
