jQuery(document).ready(function ($) {
    // your page initialization code here
    // the DOM will be available here
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview')
    });
    scanner.addListener('scan', function (content) {
        console.log(content);
        $.ajax({
            type: "POST",
            url: ajax_object.ajax_url,
            data: {
                action: 'insert_in_db',
                content: content
            },
            success: function (response) {
                alert("Entrada Registrada" + response);
            },
            error: function (response){
                alert("Error:"+response);
            }
        });
        
    });
    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            console.error('No cameras found.');
        }
    }).catch(function (e) {
        console.error(e);
    });
});