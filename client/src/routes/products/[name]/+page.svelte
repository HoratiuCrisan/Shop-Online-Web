<script lang="ts">
    import { page } from '$app/stores';
    import { onMount, onDestroy } from 'svelte';
    import { getProductByName } from '../../../api/products';
    import ProductDetails from '../../../components/ProductDetails.svelte';
    import type Product from '../../../interfaces/products';

    let name: string;
    let product: Product;
    let loading = true;

    // Subscribe to the page store to get the product name from the URL
    const unsubscribe = page.subscribe(($page) => {
        name = $page.params.name;
    });

    const fetchProductData = async (productName: string) => {
        loading = true;
        try {
            const response = await getProductByName(productName);

            if (response === undefined || response.product === undefined) {
                throw new Error("Error fetching product data");
            }

            product = response.product;
            console.log(product);
        } catch (error) {
            console.error("Error fetching product by name: " + error.message);
        } finally {
            loading = false;
        }
    };

    // Fetch product data on component mount
    onMount(() => {
        fetchProductData(name);
    });

    // Refetch product data whenever the name parameter changes
    $: {
        fetchProductData(name);
    }

    // Clear product data on component destroy
    onDestroy(() => {
        product = undefined;
        unsubscribe();
    });
</script>

{#if loading}
    <p>Loading...</p>
{:else if product}
    <div class="w-full flex h-screen bg-gray-50">
        <ProductDetails product={product} />
    </div>
{/if}
