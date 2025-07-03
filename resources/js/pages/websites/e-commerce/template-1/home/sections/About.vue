<template>
  <section id="about" class="section-animate mt-40 flex flex-col gap-8">
    <!-- Header -->
    <div class="text-center">
      <h2 class="text-active text-3xl font-bold md:text-4xl">About Us</h2>
      <p class="mx-auto mt-3 max-w-2xl text-body-muted text-base md:text-lg">
        Discover who we are and what makes us your best choice for online shopping. We are committed to providing you with the best products, seamless experiences, and exceptional customer service.
      </p>
    </div>

    <!-- Features / Highlights -->
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
      <div class="about-feature-card flex flex-col items-center rounded-xl bg-gradient-to-br from-black/2 to-black/6 p-6 shadow dark:from-white/2 dark:to-white/6">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary/10">
          <svg class="size-7 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M3 3h18v18H3V3z" />
            <path d="M9 9h6v6H9V9z" />
          </svg>
        </div>
        <h3 class="text-active text-lg font-semibold mb-2">Wide Product Selection</h3>
        <p class="text-body-muted text-center text-sm">
          Choose from a vast range of products across multiple categories, handpicked for quality and value.
        </p>
      </div>
      <div class="about-feature-card flex flex-col items-center rounded-xl bg-gradient-to-br from-black/2 to-black/6 p-6 shadow dark:from-white/2 dark:to-white/6">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary/10">
          <svg class="size-7 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 8v4l3 3" />
            <circle cx="12" cy="12" r="10" />
          </svg>
        </div>
        <h3 class="text-active text-lg font-semibold mb-2">Fast & Secure Delivery</h3>
        <p class="text-body-muted text-center text-sm">
          Enjoy quick, reliable, and secure shipping to your doorstep, every time you shop with us.
        </p>
      </div>
      <div class="about-feature-card flex flex-col items-center rounded-xl bg-gradient-to-br from-black/2 to-black/6 p-6 shadow dark:from-white/2 dark:to-white/6">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary/10">
          <svg class="size-7 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 17l-5-5h3V7h4v5h3l-5 5z" />
          </svg>
        </div>
        <h3 class="text-active text-lg font-semibold mb-2">Customer Satisfaction</h3>
        <p class="text-body-muted text-center text-sm">
          Our support team is always here to help, ensuring your shopping experience is smooth and enjoyable.
        </p>
      </div>
    </div>
  </section>
</template>
