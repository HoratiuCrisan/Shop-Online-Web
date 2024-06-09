<script lang="ts">
    import SearchBar from "./SearchBar.svelte";
    import IoMdCart from 'svelte-icons/io/IoMdCart.svelte';
    import {goto} from '$app/navigation'

    const handleLogoClick = () => {
        goto('/');
    }

    const handleKey = (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            handleLogoClick();
        }
    }
</script>

<nav class="fixed flex w-full justify-between bg-blue-500 text-white text-md md:text-lg xl:text-xl px-2 z-20">
    <span
        on:click={handleLogoClick}
        role="button"
        on:keydown={handleKey}
        tabindex=0
        class="w-1/5 my-6"
    >
        Online store
    </span>
    
    <div class="w-3/4 md:w-5/6 my-4">
        <SearchBar />
    </div>

    <div class="flex justify-between mx-2 py-4">
        <span class="w-14 text-white cursor-pointer hover:text-gray-400 mx-8 my-1 ">
            <IoMdCart />
        </span>
        <img 
            src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png"
            alt="profile"
            class="w-10 h-10 rounded-full"
        >
    </div>
</nav>