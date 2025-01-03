<x-App>
    <x-slot name="content">
        <div class="flex flex-wrap bg-[url('https://th.bing.com/th/id/OIP.CyyzDsXdZ2mk3HbUCv4THQHaEK?rs=1&pid=ImgDetMain')]">
            <div class="w-2/3">
              <div class="grid grid-cols-1 gap-6 p-5 h-full">
                <!-- Window Groups Section -->
                <div class="p-6 bg-white border border-gray-300 rounded-lg shadow-2xl flex flex-col h-full">
                  <h2 class="text-3xl font-semibold text-gray-900 mb-6 flex items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414z" clip-rule="evenodd" />
                      </svg>
                    Calling
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
                                              <span class="text-lg">{{ $queuedPerson->code }} - Head to {{$windowGroup->window_name}}</span>
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
                <div class="p-6 bg-white border border-gray-300 rounded-lg shadow-2xl flex flex-col w-12/12 h-full">
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
                <div class="relative h-screen bg-white shadow-lg overflow-hidden rounded-lg shadow-inner" data-twe-carousel-init>
                  <!-- Carousel wrapper -->
                  <div class="carousel-container relative flex transition-transform duration-500 ease-in-out w-full h-full" style="transform: translateX(0);">
                    <!-- First item -->
                    <div class="carousel-item relative flex-shrink-0 w-full h-full">
                      <img src="https://psdfreebies.com/wp-content/uploads/2020/07/Kids-School-Admission-Flyer-PSD-Template-Preview.jpg" 
                           class="block w-full h-full object-cover" alt="First Image" />
                      <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                    </div>
                    <!-- Second item -->
                    <div class="carousel-item relative flex-shrink-0 w-full h-full">
                      <img src="https://th.bing.com/th/id/OIP.6REoTayV8m0VqhXDA0VRCgHaHa?rs=1&pid=ImgDetMain" 
                           class="block w-full h-full object-cover" alt="Second Image" />
                      <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                    </div>
                    <!-- Third item -->
                    <div class="carousel-item relative flex-shrink-0 w-full h-full">
                      <img src="https://th.bing.com/th/id/OIP.sJ26EgWpUjqdEybhpqajIwHaJP?rs=1&pid=ImgDetMain" 
                           class="block w-full h-full object-cover" alt="Third Image" />
                      <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                    </div>
                  </div>
                </div>
              </div>
              
        </div>          
    </x-slot>
</x-App>



<script>
    document.addEventListener('DOMContentLoaded', function () {
      const carouselContainer = document.querySelector('.carousel-container');
      const carouselItems = document.querySelectorAll('.carousel-item');
      const itemWidth = carouselItems[0].offsetWidth; // Width of a single item
      let activeIndex = 0;
      const intervalTime = 3000; // 3 seconds interval to switch to the next image
  
      function swipeToNext() {
        // Update active index
        activeIndex = (activeIndex + 1) % carouselItems.length;
  
        // Calculate new translateX value
        const translateX = -activeIndex * itemWidth;
  
        // Apply the transform to swipe
        carouselContainer.style.transform = `translateX(${translateX}px)`;
      }
  
      // Set interval for the swipe effect
      setInterval(swipeToNext, intervalTime);
  
      // Ensure correct dimensions are applied on window resize
      window.addEventListener('resize', () => {
        const newWidth = carouselItems[0].offsetWidth;
        const translateX = -activeIndex * newWidth;
        carouselContainer.style.transform = `translateX(${translateX}px)`;
      });
    });
  </script>