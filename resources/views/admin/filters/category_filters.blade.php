<?php 
    use App\Models\ProductsFilter; 
    $productFilters = ProductsFilter::productFilters();
    // dd($productFilters); die;
    if(isset($product['category_id'])){
        $category_id = $product['category_id'];
    }
?>
@foreach ($productFilters as $filter)
    @if(isset($category_id))
    <?php 
        $filterAvailable = ProductsFilter::filterAvailable($filter['id'], $category_id);
    ?>
        @if($filterAvailable == "Yes")
            <div class="form-group">
                <label for="{{ $filter['filter_column'] }}">Select Brand</label>
                <select name="{{ $filter['filter_column'] }}" id="{{ $filter['filter_column'] }}" class="form-control text-dark">
                    <option value="">Select {{ $filter['filter_name'] }}</option>
                    @foreach($filter['filter_values'] as $value)
                        <option value="{{ $value['filter_value'] }}" @if(!empty($filter['filter_column']) && $filter['filter_column'] == $value['filter_value']) selected="" @endif>{{ ucwords($value['filter_value']) }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    @endif
@endforeach