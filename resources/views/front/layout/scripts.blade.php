<?php 
    use App\Models\ProductsFilter; 
    $productFilters = ProductsFilter::productFilters();
    // dd($productFilters); die;
?>
<script>
$(document).ready(function(){
    // Sort by Filter
    $("#sort").on("change", function(){
        // this.form.submit(); 
        var sort = $("#sort").val();
        var url = $("#url").val();
        // var fabric = get_filter('filter');
        // alert(url); return false;

        @foreach ($productFilters as $filters)              
            var {{ $filters['filter_column'] }} = get_filter('{{ $filters['filter_column'] }}');
        @endforeach

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
           url : url,
           method: 'Post', 
        //    data: {sort: sort, url: url, fabric: fabric}, 
            data: {
            @foreach($productFilters as $filters)
                {{ $filters['filter_column'] }} : {{ $filters['filter_column'] }}, 
            @endforeach
            url: url, sort: sort}, 
           success: function(data){
            $('.filter_products').html(data);
           }, 
           error: function(){
            alert("Error");
           }
        });
    });

    // Size Filter
    $(".size").on("change", function(){
        // this.form.submit(); 
        var size = get_filter('size'); 
        var sort = $("#sort").val();
        var url = $("#url").val();
        // var fabric = get_filter('filter');
        // alert(url); return false;
        // alert(size); return false;

        @foreach ($productFilters as $filters)              
            var {{ $filters['filter_column'] }} = get_filter('{{ $filters['filter_column'] }}');
        @endforeach

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
           url : url,
           method: 'Post', 
        //    data: {sort: sort, url: url, fabric: fabric}, 
            data: {
            @foreach($productFilters as $filters)
                {{ $filters['filter_column'] }} : {{ $filters['filter_column'] }}, 
            @endforeach
            url: url, sort: sort, size: size}, 
           success: function(data){
            $('.filter_products').html(data);
           }, 
           error: function(){
            alert("Error");
           }
        });
    });

    // Color Filter
    $(".color").on("change", function(){
        // this.form.submit(); 
        var color = get_filter('color'); 
        var size = get_filter('size'); 
        var sort = $("#sort").val();
        var url = $("#url").val();
        // var fabric = get_filter('filter');
        // alert(url); return false;
        // alert(size); return false;

        @foreach ($productFilters as $filters)              
            var {{ $filters['filter_column'] }} = get_filter('{{ $filters['filter_column'] }}');
        @endforeach

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
           url : url,
           method: 'Post', 
        //    data: {sort: sort, url: url, fabric: fabric}, 
            data: {
            @foreach($productFilters as $filters)
                {{ $filters['filter_column'] }} : {{ $filters['filter_column'] }}, 
            @endforeach
            url: url, sort: sort, size: size, color: color}, 
           success: function(data){
            $('.filter_products').html(data);
           }, 
           error: function(){
            alert("Error");
           }
        });
    });

    // Dynamic Filters
    @foreach($productFilters as $filter)
        $('.{{ $filter['filter_column'] }}').on('click', function(){
            var url = $("#url").val();
            var sort = $("#sort option:selected").val();
            @foreach ($productFilters as $filters)              
                var {{ $filters['filter_column'] }} = get_filter('{{ $filters['filter_column'] }}');
            @endforeach

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : url, 
                method: "Post", 
                data: {
                    @foreach($productFilters as $filters)
                        {{ $filters['filter_column'] }} : {{ $filters['filter_column'] }}, 
                    @endforeach
                    url: url, sort: sort
                }, 
                success : function(data){
                    // console.log(data);
                    $('.filter_products').html(data); 
                }, 
                error: function(){
                    alert("Error"); 
                } 
            });
        });
    @endforeach
}); 

</script>