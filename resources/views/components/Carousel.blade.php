
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

  <script>
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
  </script>