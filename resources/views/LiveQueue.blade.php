<x-App>
    <x-slot name="content">
        <div class="flex flex-wrap">
            <div class="w-2/3">
              <div class="grid grid-cols-1 gap-6 p-5 h-full bg-green-200">
                <!-- Window Groups Section -->
                <div class="p-6 bg-white border border-gray-300 rounded-lg shadow-lg flex flex-col h-full">
                  <h2 class="text-3xl font-semibold text-gray-900 mb-6 flex items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414z" clip-rule="evenodd" />
                      </svg>
                      Window Groups
                  </h2>
                  @if ($windowGroups->isNotEmpty())
                      @foreach ($windowGroups as $windowGroup)
                          <div class="mb-6 p-5 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                              <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                      <path fill-rule="evenodd" d="M9 3a7 7 0 1114 14A7 7 0 019 3zm0 1a6 6 0 10-.001 12.001A6 6 0 009 4z" clip-rule="evenodd" />
                                  </svg>
                                  {{ $windowGroup->name }}
                              </h3>
                              @if ($windowGroup->queuedPeople->isNotEmpty())
                                  <ul class="mt-4 pl-6 list-disc text-lg text-gray-700 space-y-2">
                                      @foreach ($windowGroup->queuedPeople as $queuedPerson)
                                          <li class="flex items-center text-lg">
                                              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0 1a9 9 0 110-18 9 9 0 010 18z" clip-rule="evenodd" />
                                              </svg>
                                              <span class="text-lg">{{ $queuedPerson->name }} - {{ $queuedPerson->status }}</span>
                                          </li>
                                      @endforeach
                                  </ul>
                              @else
                                  <p class="text-lg text-gray-600">Waiting</p>
                              @endif
                          </div>
                      @endforeach
                  @else
                      <p class="text-lg text-gray-600">No window groups found.</p>
                  @endif
                </div>

                <!-- Queued Users Section -->
                <div class="p-6 bg-white border border-gray-300 rounded-lg shadow-lg flex flex-col w-12/12 h-full">
                  <h2 class="text-3xl font-semibold text-gray-900 mb-6 flex items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0 1a9 9 0 110-18 9 9 0 010 18z" clip-rule="evenodd" />
                      </svg>
                      Please prepare
                  </h2>
                  @if ($queued->isNotEmpty())
                      <ul class="pl-6 list-disc text-lg text-gray-700 space-y-2">
                          @foreach ($queued as $queueItem)
                              <li class="flex items-center text-lg">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                      <path fill-rule="evenodd" d="M9 3a7 7 0 1114 14A7 7 0 019 3zm0 1a6 6 0 10-.001 12.001A6 6 0 009 4z" clip-rule="evenodd" />
                                  </svg>
                                  <span class="text-lg">{{ $queueItem->code }} - {{ $queueItem->created_at }}</span>
                              </li>
                          @endforeach
                      </ul>
                  @else
                      <p class="text-lg text-gray-600">Queue Empty</p>
                  @endif
                </div>
              </div>
            </div>

            <div class="w-1/3">
                <div
                class="relative h-screen"
                data-twe-carousel-init
                data-twe-ride="carousel">
                <!--Carousel items-->
                <div
                  class="relative w-full overflow-hidden after:clear-both after:block after:content-['']">
                  <!--First item-->
                  <div
                    class="relative float-left -mr-[100%] w-full transition-transform duration-[600ms] ease-in-out motion-reduce:transition-none"
                    data-twe-carousel-item
                    data-twe-carousel-active>
                    <img
                      src="https://mdbcdn.b-cdn.net/img/new/slides/041.webp"
                      class="block w-full object-cover h-full"
                      alt="Wild Landscape" />
                  </div>
                  <!--Second item-->
                  <div
                    class="relative float-left -mr-[100%] hidden w-full transition-transform duration-[600ms] ease-in-out motion-reduce:transition-none"
                    data-twe-carousel-item>
                    <img
                      src="https://mdbcdn.b-cdn.net/img/new/slides/042.webp"
                      class="block w-full object-cover h-full"
                      alt="Camera" />
                  </div>
                  <!--Third item-->
                  <div
                    class="relative float-left -mr-[100%] hidden w-full transition-transform duration-[600ms] ease-in-out motion-reduce:transition-none"
                    data-twe-carousel-item>
                    <img
                      src="https://mdbcdn.b-cdn.net/img/new/slides/043.webp"
                      class="block w-full object-cover h-full"
                      alt="Exotic Fruits" />
                  </div>
                </div>
              </div>               
            </div>
        </div>          
    </x-slot>
</x-App>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const carouselItems = document.querySelectorAll('[data-twe-carousel-item]');
    let activeIndex = 0;
    const intervalTime = 2000; // 2 seconds interval to switch to next image

    function showNextItem() {
      // Hide current active item
      carouselItems[activeIndex].classList.add('hidden');

      // Move to next item
      activeIndex = (activeIndex + 1) % carouselItems.length;

      // Show the next item
      carouselItems[activeIndex].classList.remove('hidden');
    }

    // Initial display setup
    carouselItems[activeIndex].classList.remove('hidden');
    setInterval(showNextItem, intervalTime); // Adjusts how frequently it moves to next image
  });
</script>