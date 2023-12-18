<?php
namespace Hibrido\UrlManage\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\View\Page\Config;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $storeManager;
    protected $pageFactory;
    protected $httpContext;
    protected $pageConfig;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        PageFactory $pageFactory,
        HttpContext $httpContext,
        Config $pageConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->pageFactory = $pageFactory;
        $this->httpContext = $httpContext;
        $this->pageConfig = $pageConfig;
    }

    public function getCustomContent()
    {
        $storeId = $this->storeManager->getStore()->getId();
        $pageId = $this->getCurrentPageId();

        $content = "";
        $content .= "<br>Store ID: $storeId";
        $content .= "<br>Page ID: $pageId";

        if ($this->isMultistore()) {
            $content .= "<br>Esta é uma página multistore.";
            $content .= $this->getHreflangMetaTags();
        } else {
            $content .= "<br>Esta não é uma página multistore.";
        }

        return $content;
    }

    protected function getCurrentPageId()
    {
        $pageId = null;
        $currentPage = $this->pageFactory->create()->setStoreId($this->storeManager->getStore()->getId());

        if ($currentPage->getId()) {
            $pageId = $currentPage->getId();
        }

        return $pageId;
    }

    protected function isMultistore()
    {
        $isMultistore = false;

        /** @var StoreInterface[] $stores */
        $stores = $this->storeManager->getStores();

        if (count($stores) > 1) {
            $isMultistore = true;
        }

        return $isMultistore;
    }

    protected function getHreflangMetaTags()
    {
        $tags = '';
        $currentStore = $this->storeManager->getStore();
        $stores = $this->storeManager->getStores();
    
        foreach ($stores as $store) {
            $storeCode = $store->getCode();
            $storeUrl = $store->getBaseUrl();
            $storeLocale = $store->getConfig('general/locale/code');
            $storeLanguage = $this->getLocaleLanguageCode($storeLocale);
    
            $isActive = $this->httpContext->getValue(\Magento\Store\Model\Store::ENTITY, 'is_active', $store->getId());
    
            if ($isActive && $currentStore->getId() !== $store->getId()) {
                $cmsPageUrl = $this->getCmsPageUrl($store);
                $tags .= "<link rel=\"alternate\" hreflang=\"$storeLanguage\" href=\"$storeUrl$cmsPageUrl\" />\n";
            }
        }
    
        return $tags;
    }

    protected function getLocaleLanguageCode($locale)
    {
        $languageCodes = [
            'en_US' => 'en',
            'en_GB' => 'en',
            'pt_BR' => 'pt',
        ];
    
        return $languageCodes[$locale] ?? $locale;
    }

    protected function getCmsPageUrl($store)
    {
        $pageIdentifier = 'home';
    
        $page = $this->pageFactory->create()->setStoreId($store->getId())->load($pageIdentifier, 'identifier');
        
        if ($page->getId()) {
            return $page->getIdentifier();
        }
    
        return '';
    }
}
