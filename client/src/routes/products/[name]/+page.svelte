<script lang="ts" >
    import {page} from '$app/stores';
    import { onMount } from 'svelte';
    import {getProductByName} from '../../../api/products'
    import ProductDetails from '../../../components/ProductDetails.svelte'
    import type Product from '../../../interfaces/products'

    let name = $page.params.name;
    let product: Product;
    let loading = true;

    onMount(async () => {
        try {
            const response = await getProductByName(name);

            if (response === undefined || response.product === undefined) {
                throw new Error("Error at fetching product data");
            }

            product = response.product;
            console.log(product);
            loading = false;
        } catch (error) {
            console.error("Error at fetching product by name: " + error.message);
        }
    })
</script>

{#if loading}
    <p>Loading...</p>
{:else if product}
    <div class="w-full flex h-screen bg-gray-50">
        <ProductDetails product={product} />
    </div>
{/if}


