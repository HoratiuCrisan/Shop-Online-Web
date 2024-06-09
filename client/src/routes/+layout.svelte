<script>
    import "../app.css";
    import Navbar from "../components/Navbar.svelte";
	import Sidebar from "../components/Sidebar.svelte";
  </script>
  
  <Navbar />
  <div class="flex min-h-screen">
    <Sidebar />
    <div class="flex-1 p-4 bg-gray-50 z-10">
      <slot />
    </div>
  </div>