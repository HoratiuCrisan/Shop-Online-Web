<script lang="ts">
    import { getAllProducts } from "../api/products";
    import {goto} from '$app/navigation';
    import {writable} from 'svelte/store';
    import type Product from "../interfaces/products";
    import { onMount } from "svelte";

    let products: Product[] = [];

    const searchQuery = writable("");

    const fetchProducts = async () => {
        try {
            const response = await getAllProducts();

            if (!response) {
                throw new Error("Failed to get all products");
            }

            products = response.products;
        } catch (error) {
            console.error("Failed to fetch products for search query: " + error);
        }
    }

    const handleSearch = (event) => {
        searchQuery.set(event.target.value);
    } 

    const handleNavigation = (event) => {
        if (event.keydown === 'Enter') {
            goto(`/products`);
        }
    }

    const handleProductNavigation = (name: string) => {
        goto(`/products/${name}`);
        searchQuery.set(null);
    }

    const handleProductKey = () => {}

    onMount(fetchProducts);

</script>

<div class="block w-full fixed justify-center items-center z-30">
    <input 
        type="text" 
        placeholder="Search for a product..."
        class="w-2/4 rounded-lg text-black p-2"
        on:input={handleSearch}
        on:keydown={handleNavigation}
    >

    {#if $searchQuery && $searchQuery.length > 0}
    <ul class="bg-white w-2/4 text-black rounded-md shadow-xl mt-2 p-4">
        {#each products.filter(product => product.name.toLowerCase().includes($searchQuery.toLowerCase()) || product.category.toLowerCase().includes($searchQuery.toLowerCase())).slice(0, 5) as product}
        <div
            on:click={() => handleProductNavigation(product.name)}
            on:keydown={handleProductKey}
            role="button"
            tabindex=0
            class="hover:bg-gray-100 hover:rounded-md cursor-pointer px-4 py-2"
        >
            {product.name}
        </div>
      {/each}
    </ul>
  {/if}
</div>