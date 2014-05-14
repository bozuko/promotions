jQuery(function($){
  $('[data-action="cleanup"]').on('click', function(e){
    if( !confirm('This will delete all registrations and entries associated with this promotion. Are you sure?')){
      e.preventDefault();
    }
  });
});