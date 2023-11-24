<form id="search" role="search" method="get" class="search-form hidden" action="{{ home_url('/') }}">
  <label>
    <span class="sr-only">
      {{ _x('Search for:', 'label', 'san-carlo-theme') }}
    </span>

    <input
      type="search"
      placeholder="{!! esc_attr_x('Find shows or info', 'placeholder', 'san-carlo-theme') !!}"
      value="{{ get_search_query() }}"
      name="s"
    >
  </label>

  {{-- <button>{{ _x('Search', 'submit button', 'san-carlo-theme') }}</button> --}}
  <i id="close-form-search" class="bf-icon icon-remove"></i>
</form>
