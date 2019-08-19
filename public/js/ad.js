// Add a new entry
$('#add-image').click(function(){
    // Index which have been geting with the value of input widgets-counter                            
    var index = +$('#widgets-counter').val();

    // We get the prototype of entries and we replace __name__ by the good entry index
    const template = $('#ad_images').data('prototype').replace(/__name__/g, index);

    // We append in to ad_images the tmpl
    $('#ad_images').append(template);

    // Incrementation of value of index
    index++;

    // Changing value of inpu widgets-counter by the new index value
    $('#widgets-counter').val(index);

    // Call handleDeleteButtons()
    handleDeleteButtons();
});

// Function which will to administrate the deletes buttons
function handleDeleteButtons(){

    // Find all buttons which have data-action='delete' property
    $('button[data-action="delete"]').on('click',function(){
        // We get the corresponding target
        const target = this.dataset.target;
        
        // We remove the good entry block
        $(target).remove();
    });
}

// Call handleDeleteButtons()
handleDeleteButtons();