<?php
/**
 * Example: Get a product from the product repository.
 *
 * Run with `php script.php product.php`
 */

declare(strict_types=1);

/**
 * @var \Magento\Framework\ObjectManagerInterface $om
 * @var \Magento\Catalog\Api\ProductRepositoryInterface $pr
 * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory $scf
 */
$pr = $om->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
$scf = $om->get(\Magento\Framework\Api\SearchCriteriaBuilderFactory::class);
$sc = $scf->create()->setPageSize(1)->setCurrentPage(1)->create();
$l = $pr->getList($sc)->getItems();

foreach ($l as $p) {
    var_dump($p->getSku());
}
