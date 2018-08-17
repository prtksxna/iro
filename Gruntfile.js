module.exports = function(grunt) {
  // Plugins
  grunt.loadNpmTasks( 'grunt-wp-i18n' );

  // Config
  grunt.initConfig({
    makepot: {
        target: {
            options: {
                type: 'wp-plugin'
            }
        }
    },
  });

  // Tasks
  grunt.registerTask('default', ['makepot']);
};
