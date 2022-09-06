require('./bootstrap');

window.addEventListener( 'load', function() {
    console.log('js working')

    
    // $('.tab-a').click(function(){  
    //     $(".tab").removeClass('tab-active');
    //     $(".tab[data-id='"+$(this).attr('data-id')+"']").addClass("tab-active");
    //     $(".tab-a").removeClass('active-a');
    //     $(this).parent().find(".tab-a").addClass('active-a');
    // });

    triggers = document.querySelectorAll('.tab-a');
    triggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var content = document.querySelector('.tab[data-id="' + id + '"]');
            var activeTabs = document.querySelectorAll('.tab-active');
            var activeTriggers = document.querySelectorAll('.active-a');

            activeTabs.forEach(function(activeTab) {
                activeTab.classList.remove('tab-active');
            });
            activeTriggers.forEach(function(activeTrigger) {
                activeTrigger.classList.remove('active-a');
            });

            trigger.classList.add('active-a');
            
            content.classList.add('tab-active');
        });
    });
})

