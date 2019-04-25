<div id="sidepanel" class="sidepanel">
  <a href="#">About</a>
  <a href="#">Services</a>
  <a href="#">Clients</a>
  <a href="#">Contact</a>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript">

$(document).click(function(event) { 
    //Toggle sidepanel
    if($(event.target).closest('#sidepanel-link').length) {
        $('#sidepanel').toggleClass('sidepanel-active');
    }
    //Side panel open and detecting click
    else if ($('#sidepanel').hasClass('sidepanel-active')){
        //Not sidepanel or navbar
        if(!$(event.target).closest('.sidepanel').length && !$(event.target).closest('#pnProductNav').length){
            $('#sidepanel').toggleClass('sidepanel-active');
            event.preventDefault();
        }
    }    
});
</script>