@if ($model)
    <div class="d-flex justify-content-center">
        {{ $model->withQueryString()->onEachSide(0)->links() }}
    </div>
@endif
