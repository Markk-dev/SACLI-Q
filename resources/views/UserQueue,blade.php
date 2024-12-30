<x-App>
    <x-slot name="content">
        <div class="flex flex-wrap">
            <div class="w-2/3">
                dasd
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
          
    </x-slot>

</x-App>