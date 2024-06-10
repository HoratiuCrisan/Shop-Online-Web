<script>
  import "../app.css";
  import Navbar from "../components/Navbar.svelte";
  import Sidebar from "../components/Sidebar.svelte";
  import { page } from '$app/stores';

  // Subscribe to the page store
  let path;
  $: {
      path = $page.url.pathname;
  }

  // Define paths where Navbar and Sidebar should not be shown
  const excludedPaths = ['/login', '/register'];
</script>

{#if !excludedPaths.includes(path)}
  <Navbar />
{/if}

<div class="flex min-h-screen">
  {#if !excludedPaths.includes(path)}
      <Sidebar />
  {/if}
  <div class="flex-1 p-4 bg-gray-50 z-10">
      <slot />
  </div>
</div>
