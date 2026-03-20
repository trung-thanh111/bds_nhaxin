@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
<x-backend.delete
    :model="$record"
    submitRoute="location_highlight.destroy" />