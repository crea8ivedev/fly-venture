@if($content->hide_section !== 'yes')
@php
  $openings = $content->openings ?? [];
  $locations = [];
  foreach ($openings as $opening) {
    $slug = sanitize_title($opening['location'] ?? '');
    $label = $opening['location'] ?? '';
    if ($slug && !isset($locations[$slug])) {
      $locations[$slug] = $label;
    }
  }
@endphp
<section
  id="{{ $content->id ?? '' }}"
  class="employment-openings-section {{ $content->class ?? '' }}"
>
  <div class="container-fluid">

    {{-- Section Header --}}
    <div class="employment-openings-head">

      @if(!empty($content->icon))
        <div class="employment-openings-icon">
          <img
            src="{{ esc_url($content->icon['url']) }}"
            height="{{ $content->icon['height'] ?? 100 }}"
            width="{{ $content->icon['width'] ?? 92 }}"
            alt="{{ esc_attr($content->icon['alt'] ?? '') }}"
          >
        </div>
      @endif

      @if(!empty($content->main_title))
        <div class="title title-blue">
          <h2>{{ $content->main_title }}</h2>
        </div>
      @endif

      @if(!empty($content->main_description))
        <div class="content">
          {!! $content->main_description !!}
        </div>
      @endif

      {{-- Filter Buttons --}}
      @if(!empty($locations))
        <div class="employment-filter" data-employment-filter>
          <button class="employment-filter-btn" type="button" data-filter="all">All</button>
          @foreach($locations as $slug => $label)
            <button class="employment-filter-btn" type="button" data-filter="{{ esc_attr($slug) }}">
              {{ $label }}
            </button>
          @endforeach
        </div>
      @endif

    </div>

    {{-- Job Listings --}}
    @if(!empty($openings))
      <div class="employment-listings" data-employment-listings>
        @foreach($openings as $opening)
          @php $location_slug = sanitize_title($opening['location'] ?? ''); @endphp
          <div class="employment-card" data-location="{{ esc_attr($location_slug) }}">

            <div class="employment-card-main">

              {{-- Job Title + Description --}}
              <div class="employment-card-section global-list">
                @if(!empty($opening['job_title']))
                  <div class="title title-blue employment-card-title">
                    <h3>{{ $opening['job_title'] }}</h3>
                  </div>
                @endif

                @if(!empty($opening['job_description']))
                  <div class="content employment-card-copy">
                    {!! $opening['job_description'] !!}
                  </div>
                @endif
              </div>

              {{-- Required Skills --}}
              @if(!empty($opening['required_skills']))
                <div class="employment-card-section global-list">
                  @if(!empty($opening['required_skills_heading']))
                    <div class="title title-blue employment-card-title">
                      <h3>{{ $opening['required_skills_heading'] }}</h3>
                    </div>
                  @endif
                  <div class="content">
                    {!! $opening['required_skills'] !!}
                  </div>
                </div>
              @endif

              {{-- Preferred Skills --}}
              @if(!empty($opening['preferred_skills']))
                <div class="employment-card-section global-list">
                  @if(!empty($opening['preferred_skills_heading']))
                    <div class="title title-blue employment-card-title">
                      <h3>{{ $opening['preferred_skills_heading'] }}</h3>
                    </div>
                  @endif
                  <div class="content">
                    {!! $opening['preferred_skills'] !!}
                  </div>
                </div>
              @endif

            </div>

            {{-- Job Details Sidebar --}}
            @if(!empty($opening['job_details']))
              <aside class="employment-card-side">
                <div class="employment-job-detail-box">
                  @if(!empty($opening['job_details_heading']))
                    <h4>{{ $opening['job_details_heading'] }}</h4>
                  @endif
                  @foreach($opening['job_details'] as $detail)
                    @if(!empty($detail['jd_title']) || !empty($detail['jd_description']))
                      <p>
                        @if(!empty($detail['jd_title']))
                          <strong>{{ $detail['jd_title'] }}</strong>
                        @endif
                        {{ $detail['jd_description'] ?? '' }}
                      </p>
                    @endif
                  @endforeach
                </div>
              </aside>
            @endif

          </div>
        @endforeach
      </div>
    @endif

  </div>

  <div class="employment-empty-state hidden" data-employment-empty>
    <div class="container-fluid">
      <div class="content">
        <p>No openings are listed for this location right now. Please check another location or contact HR for upcoming roles.</p>
      </div>
    </div>
  </div>

</section>
@endif
