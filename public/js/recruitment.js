$('.change-reason').click(function (e) {
    if(this.value == "change")
    {
        $('#change').show();
        $('#new').hide();
    }
    else if(this.value == "new")
    {
        console.log('new')
        $('#change').hide();
        $('#new').show();
    }
    else
    {
        $('#change').hide();
        $('#new').hide();
    }
});


