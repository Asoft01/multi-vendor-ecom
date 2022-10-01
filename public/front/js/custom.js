$(document).ready(function(){
    // alert("test"); die;
//     $("#sort").on("change", function(){
//         // this.form.submit(); 
//         var sort = $("#sort").val();
//         var url = $("#url").val();
//         var fabric = get_filter('filter');
//         // alert(url); return false;
//         $.ajax({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//            url : url,
//            method: 'Post', 
//            data: {sort: sort, url: url}, 
//            success: function(data){
//             $('.filter_products').html(data);
//            }, 
//            error: function(){
//             alert("Error");
//            }
//         });
//     });

    $("#getPrice").change(function(){
        var size = $(this).val(); 
        var product_id = $(this).attr("product-id"); 
        // alert(size);
        // alert(product_id); die;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get-product-price', 
            data: {size: size, product_id : product_id},
            type: 'post', 
            success: function(resp){
                alert(resp);
            }, error: function(){
                alert("Error");
            }
        });
    });
}); 

// $('.fabric').on('click', function(){
//     var url = $("#url").val();
//     var sort = $("#sort option:selected").val();
//     var fabric= get_filter('fabric');
//     $.ajax({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         url : url, 
//         method: "Post", 
//         data: {url: url, sort: sort, fabric: fabric}, 
//         success : function(data){
//             // console.log(data);
//             $('.filter_products').html(data); 
//         }, error: function(){
//             alert("Error"); 
//         }
//     });
// });

function get_filter(class_name){
    var filter = []; 
    $('.'+class_name+ ':checked').each(function(){
        filter.push($(this).val());
    });
    return filter;
}

