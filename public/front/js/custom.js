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
                // alert(resp);
                // alert(resp['final_price']);
                // alert(resp['discount']); 
                if(resp['discount'] > 0){
                    $(".getAttributePrice").html("<div class='price'><h4>Rs."+ resp['final_price'] +"</h4></div><div class='original-price'><span>Original Price: </span><span>Rs."+ resp['product_price'] +"</span></div>");
                }else{
                    $(".getAttributePrice").html("<div class='price'><h4>Rs."+ resp['final_price'] +"</h4></div>");
                }
            }, error: function(){
                alert("Error");
            }
        });
    });

    // Update Cart Items Qty
    $(document).on('click', '.updateCartItem', function(){
        // alert("test");
        if($(this).hasClass('plus-a')){
            // Get Qty
            var quantity = $(this).data('qty');
            // alert(quantity);  
            // increase the qty by 1
            new_qty = parseInt(quantity) + 1;
            // alert(new_qty);  
        }

        if($(this).hasClass('minus-a')){
            // Get Qty
            var quantity = $(this).data('qty');
            // alert(quantity);  
            // Check Qty is atleast 1
            if(quantity <= 1){
                alert("Item quantity must be 1 or greater!");
                return false;
            }
            // increase the qty by 1
            new_qty = parseInt(quantity) - 1;
            // alert(new_qty);  
        }
        var cartid = $(this).data('cartid');
        // alert(cartid); 
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {cartid: cartid, qty : new_qty}, 
            url: '/cart/update', 
            type: 'post', 
            success: function(resp){
                // alert(resp); return false
                // alert(resp.status); 
                if(resp.status == false){
                    alert(resp.message);
                }
                $("#appendCartItems").html(resp.view);
            }, error: function(){
                alert('error');
            }   
        });
    });

      // Delete Cart Items 
      $(document).on('click', '.deleteCartItem', function(){
        var cartid = $(this).data('cartid');
        var result = confirm("Are you sure to delete this Cart Item?");
        if(result){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{cartid : cartid}, 
                url: '/cart/delete', 
                type: 'post', 
                success: function(resp){
                    $("#appendCartItems").html(resp.view);
                }, error: function(){
                    alert('error');
                }   
            }) 
        } 
        // alert(cartid);
      
    });

    // Register Form Validation 
    $("#registerForm").submit(function(){
        var formdata = $(this).serialize(); 
        // alert(formdata); 
        // return false;
        $.ajax({
            url: "/user/register", 
            type: "POST", 
            data:formdata, 
            success:function(resp){
                // alert(resp.type);
                // console.log(resp);
                if(resp.type == "error"){
                    $.each(resp.errors, function(i, error){
                        // console.log(i, error);
                        $("#register-"+i).attr('style', 'color:red');
                        $("#register-"+i).html(error);
                        setTimeout(function(){
                            $("#register-"+i).css({
                                'display':'none'
                            });
                        }, 3000);
                    });
                }else if(resp.type == "success"){
                    window.location.href = resp.url
                }
                // window.location.href = resp.url;
                // alert(resp);
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

