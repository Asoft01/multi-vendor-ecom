// const { default: Swal } = require("sweetalert2");
$(document).ready(function(){
    // datatable class
    $('#sections').DataTable();
    $('#categories').DataTable();
    $('#brands').DataTable();
    $('#products').DataTable();
    $('#banners').DataTable();

    $(".nav-item").removeClass("active");
    $(".nav-link").removeClass("active");
    // Check Admin Password is correct or not
    $("#current_password").keyup(function(){
        var current_password = $("#current_password").val();
        // alert(current_password);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/check-admin-password',
            data: {current_password: current_password},
            success: function(resp){
                // alert(resp);
                if(resp == "false"){
                    $("#check_password").html("<font color='red'>Current Pasword is Incorrect! </font>");
                }else if(resp == "true"){
                    $("#check_password").html("<font color='green'>Current Password is Correct! </font>");
                }
            },
            error: function(){
                alert('Error');
            }
        })
    });

    // Update Admin Status 
    $(document).on('click', ".updateAdminStatus", function(){
        // alert("test");
        var status = $(this).children("i").attr("status");
        // alert(status);
        var admin_id = $(this).attr("admin_id");
        // alert(admin_id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-admin-status',
            data : { status : status, admin_id : admin_id }, 
            success: function(resp){
                // alert(resp);
                if(resp['status'] == 0){
                    $("#admin-"+admin_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
                }else if(resp['status'] == 1){
                    $("#admin-"+admin_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
                }
            }, 
            error: function(){
                alert("Error");
            }
        })
    });

    // Update Section Status 
    $(document).on('click', ".updateSectionStatus", function(){
        // alert("test"); return true;
        var status = $(this).children("i").attr("status");
        // alert(status); return true;
        var section_id = $(this).attr("section_id");
        // alert(section_id); return true;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-section-status',
            data : { status : status, section_id : section_id }, 
            success: function(resp){
                // alert(url); return true;
                if(resp['status'] == 0){
                    $("#section-"+section_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
                }else if(resp['status'] == 1){
                    $("#section-"+section_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
                }
            }, 
            error: function(){
                alert("Error");
            }
        })
    });

    // Update Banner Status 
    $(document).on('click', ".updateBannerStatus", function(){
        // alert("test"); return true;
        var status = $(this).children("i").attr("status");
        // alert(status); return true;
        var banner_id = $(this).attr("banner_id");
        // alert(banner_id); return true;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-banner-status',
            data : { status : status, banner_id : banner_id }, 
            success: function(resp){
                // alert(url); return true;
                if(resp['status'] == 0){
                    $("#section-"+banner_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
                }else if(resp['status'] == 1){
                    $("#section-"+banner_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
                }
            }, 
            error: function(){
                alert("Error");
            }
        })
    });

    // Update Category Status 
    $(document).on('click', ".updateCategoryStatus", function(){
        // alert("test"); return true;
        var status = $(this).children("i").attr("status");
        // alert(status); return true;
        var category_id = $(this).attr("category_id");
        // alert(category_id); return true;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-category-status',
            data : { status : status, category_id : category_id }, 
            success: function(resp){
                // alert(url); return true;
                if(resp['status'] == 0){
                    $("#category-"+category_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
                }else if(resp['status'] == 1){
                    $("#category-"+category_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
                }
            }, 
            error: function(){
                alert("Error");
            }
        })
    });

     // Update Brand Status 
     $(document).on('click', ".updateBrandStatus", function(){
        // alert("test"); return true;
        var status = $(this).children("i").attr("status");
        // alert(status); return true;
        var brand_id = $(this).attr("brand_id");
        // alert(brand_id); return true;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-brand-status',
            data : { status : status, brand_id : brand_id }, 
            success: function(resp){
                // alert(url); return true;
                if(resp['status'] == 0){
                    $("#brand-"+brand_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
                }else if(resp['status'] == 1){
                    $("#brand-"+brand_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
                }
            }, 
            error: function(){
                alert("Error");
            }
        })
    });

    // Update Product Status
    $(document).on('click', ".updateProductStatus", function(){
        // alert("test"); return true;
        var status = $(this).children("i").attr("status");
        // alert(status); return true;
        var product_id = $(this).attr("product_id");
        // alert(product_id); return true;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-product-status', 
            data: {status: status, product_id: product_id},
            success: function(resp){
                // alert(url); return true;
                if(resp['status'] == 0){
                    $("#product-"+product_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
                }else{ 
                    $("#product-"+product_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
                }
            }, 
            error: function(){
                alert("Error");
            }
        })
    });

     // Update Attribute Status
     $(document).on('click', ".updateAttributeStatus", function(){
        // alert("test"); return true;
        var status = $(this).children("i").attr("status");
        // alert(status); return true;
        var attribute_id = $(this).attr("attribute_id");
        // alert(attribute_id); return true;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-attribute-status', 
            data: {status: status, attribute_id: attribute_id},
            success: function(resp){
                // alert(url); return true;
                if(resp['status'] == 0){
                    $("#attribute-"+attribute_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
                }else{ 
                    $("#attribute-"+attribute_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
                }
            }, 
            error: function(){
                alert("Error");
            }
        })
    });
    
    // Update Attribute Status
    $(document).on('click', ".updateImageStatus", function(){
        // alert("test"); return true;
        var status = $(this).children("i").attr("status");
        // alert(status); return true;
        var image_id = $(this).attr("image_id");
        // alert(image_id); return true;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-image-status', 
            data: {status: status, image_id: image_id},
            success: function(resp){
                // alert(url); return true;
                if(resp['status'] == 0){
                    $("#image-"+image_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
                }else{ 
                    $("#image-"+image_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
                }
            }, 
            error: function(){
                alert("Error");
            }
        })
    });
    


    // Confirm Deletion (Simple Javascript)
    // $(".confirmDelete").click(function(){
    //     var title = $(this).attr("title");
    //     if(confirm("Are you sure to delete this "+title + "?")){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // });

    // Confirm Deletion (SweetAlert Library)
    $(".confirmDelete").click(function(){
        var module = $(this).attr("module");
        var moduleid = $(this).attr("moduleid");
        // alert(moduleid);

        // return false;
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              )
              window.location = "/admin/delete-"+module+"/"+moduleid;
            }
          })
    });

    // Append Categories level
    $("#section_id").change(function(){
        var section_id = $(this).val();
        // alert(section_id); return false;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: "/admin/append-categories-level", 
            data: {section_id : section_id},
            success: function(resp){
                // alert(resp);
                $("#appendCategoriesLevel").html(resp);
            }, error: function(){
                alert("Error");
            }
        });
    });

    // Products Attributes  Add/Remove Script

    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><div style="height: 10px;"></div><input type="text" name="size[]" placeholder="Size" style="width: 110px;"/>&nbsp;<input type="text" name="sku[]" placeholder="SKU" style="width: 110px;"/>&nbsp;<input type="text" name="price[]" placeholder="Price" style="width: 110px;"/>&nbsp;<input type="text" name="stock[]" placeholder="Stock" style="width: 110px;"/>&nbsp;<a href="javascript:void(0);" class="remove_button">Remove</a></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
    
})
