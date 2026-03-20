@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
@include('backend.dashboard.component.formError')
<form action="{{ route('attribute.destroy', $attribute->id) }}" method="post" class="box">
    @csrf
    @method('DELETE')
   @include('backend.dashboard.component.destroy', ['model' => $attribute])
</form>
