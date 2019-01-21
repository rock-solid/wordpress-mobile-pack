<?php

class Theme
{
    private $appName;
    
    private $appEndpoint = "https://pwa-cdn.baobabsuite.com";

    private $metaDescription;

    private $hostUrl;

    private $manifestUrl;

    private $newsItemDateFormat = "Do MMM YYYY";

    private $newsItemTimeFormat = "LT";

    private $defaultFeedPageSize = 20;

    private $bmBurgerBarsBackground = "#000";

    private $bmCrossBackground = "#ffffff";

    private $bmMenuBackground = "#fff";

    private $bmItemListColor = "#000";

    private $bmOverlayBackground = "transparent";

    private $selectedBackground = "moon-gray";

    private $selectedText = "#ee3224";

    private $themeColour = "#fff";

    private $backgroundColour = "#fff";

    private $textColour = "#000";

    private $headerImageType = 'full';

    private $headerImage;

    private $hamburgerImage;

    private $loadingSpinner;

    private $menuSlideOutWidth = '75%';

    private $DFTNetworkId = NULL; 

    private $AdUnit = ""; 

    private $AdUnitSectionExtended;

    private $GATrackingCode; 

    private $GTMID; 

    private $sectionSliderTextColor = '#000';

    private $sectionSliderBackground = '#FFF';

    private $listAdInterval;

    private $listAdMax; 

    private $highlightsColour = '#099ee2';

    private $borderColour = '#099ee2';

    private $sectionDownloadEnabled = false;

    private $routes;

    private $layout = 1;

    private $multiSection = false;

    private $flattenSections = false;

    private $serviceWorkerUrl;

    private $showDateBlockOnFeedListItem;

    private $showAllFeed = true;

    private $showClassicSwitch = false;

    private $imageGalleryHeight;

    private $mastHeadHeight;

    private $showDatesOnList = false;

    private $searchLightTheme = false;

    private $showSearch = false;

    private $searchParam;

    private $searchAction;

    private $maxWidth = 1024;

    private $priorityTags;

    private $tagConfig;

    private $topHeros;

    private $addThisCode;

    private $socialShareKitButtons = [];

    private $extraLinks;
    
    private $excludedSections = [];
    
    private $twitterEmbedUrl = "https://platform.twitter.com/widgets.js";

    private $instagramEmbedUrl = "https://www.instagram.com/embed.js";

    private $shareTitlePrefix;

    private $customStyles;

    private $hamburgerImageMarginTop;

    private $customHtml;

    private $infiniteVerticalArticleScroll = false;

    private $infiniteHorizontalArticleScroll = true;

    private $excludedPageIds = [];
    
    private $sectionPrefix = null;

    private $dnsPrefetch =  [];

    private $apiEndPoint;

    private $hasTaboola;
    
    private $renderAds;
    
    private $renderAdsServerSide;
    
    private $firstImpressionsId;
    
    private $whitelistedSections;

    private $instagramSocialUrl;

    private $twitterSocialUrl;

    private $facebookSocialUrl;

    private $youtubeSocialUrl;

    /**
     * Get the value of appName
     */
    public function getAppName()
    {
        return $this->appName;
    }

    /**
     * Set the value of appName
     *
     * @return  self
     */
    public function setAppName($appName)
    {
        $this->appName = $appName;

        return $this;
    }

    /**
     * Get the value of metaDescription
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set the value of metaDescription
     *
     * @return  self
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get the value of hostUrl
     */
    public function getHostUrl()
    {
        return $this->hostUrl;
    }

    /**
     * Set the value of hostUrl
     *
     * @return  self
     */
    public function setHostUrl($hostUrl)
    {
        $this->hostUrl = $hostUrl;

        return $this;
    }

    /**
     * Get the value of manifestUrl
     */
    public function getManifestUrl()
    {
        return $this->manifestUrl;
    }

    /**
     * Set the value of manifestUrl
     *
     * @return  self
     */
    public function setManifestUrl($manifestUrl)
    {
        $this->manifestUrl = $manifestUrl;

        return $this;
    }

    /**
     * Get the value of newsItemDateFormat
     */
    public function getNewsItemDateFormat()
    {
        return $this->newsItemDateFormat;
    }

    /**
     * Set the value of newsItemDateFormat
     *
     * @return  self
     */
    public function setNewsItemDateFormat($newsItemDateFormat)
    {
        $this->newsItemDateFormat = $newsItemDateFormat;

        return $this;
    }

    /**
     * Get the value of newsItemTimeFormat
     */
    public function getNewsItemTimeFormat()
    {
        return $this->newsItemTimeFormat;
    }

    /**
     * Set the value of newsItemTimeFormat
     *
     * @return  self
     */
    public function setNewsItemTimeFormat($newsItemTimeFormat)
    {
        $this->newsItemTimeFormat = $newsItemTimeFormat;

        return $this;
    }

    /**
     * Get the value of defaultFeedPageSize
     */
    public function getDefaultFeedPageSize()
    {
        return $this->defaultFeedPageSize;
    }

    /**
     * Set the value of defaultFeedPageSize
     *
     * @return  self
     */
    public function setDefaultFeedPageSize($defaultFeedPageSize)
    {
        $this->defaultFeedPageSize = $defaultFeedPageSize;

        return $this;
    }

    /**
     * Get the value of bmBurgerBarsBackground
     */
    public function getBmBurgerBarsBackground()
    {
        return $this->bmBurgerBarsBackground;
    }

    /**
     * Set the value of bmBurgerBarsBackground
     *
     * @return  self
     */
    public function setBmBurgerBarsBackground($bmBurgerBarsBackground)
    {
        $this->bmBurgerBarsBackground = $bmBurgerBarsBackground;

        return $this;
    }

    /**
     * Get the value of bmCrossBackground
     */
    public function getBmCrossBackground()
    {
        return $this->bmCrossBackground;
    }

    /**
     * Set the value of bmCrossBackground
     *
     * @return  self
     */
    public function setBmCrossBackground($bmCrossBackground)
    {
        $this->bmCrossBackground = $bmCrossBackground;

        return $this;
    }

    /**
     * Get the value of bmMenuBackground
     */
    public function getBmMenuBackground()
    {
        return $this->bmMenuBackground;
    }

    /**
     * Set the value of bmMenuBackground
     *
     * @return  self
     */
    public function setBmMenuBackground($bmMenuBackground)
    {
        $this->bmMenuBackground = $bmMenuBackground;

        return $this;
    }

    /**
     * Get the value of bmItemListColor
     */
    public function getBmItemListColor()
    {
        return $this->bmItemListColor;
    }

    /**
     * Set the value of bmItemListColor
     *
     * @return  self
     */
    public function setBmItemListColor($bmItemListColor)
    {
        $this->bmItemListColor = $bmItemListColor;

        return $this;
    }

    /**
     * Get the value of bmOverlayBackground
     */
    public function getBmOverlayBackground()
    {
        return $this->bmOverlayBackground;
    }

    /**
     * Set the value of bmOverlayBackground
     *
     * @return  self
     */
    public function setBmOverlayBackground($bmOverlayBackground)
    {
        $this->bmOverlayBackground = $bmOverlayBackground;

        return $this;
    }

    /**
     * Get the value of selectedBackground
     */
    public function getSelectedBackground()
    {
        return $this->selectedBackground;
    }

    /**
     * Set the value of selectedBackground
     *
     * @return  self
     */
    public function setSelectedBackground($selectedBackground)
    {
        $this->selectedBackground = $selectedBackground;

        return $this;
    }

    /**
     * Get the value of selectedText
     */
    public function getSelectedText()
    {
        return $this->selectedText;
    }

    /**
     * Set the value of selectedText
     *
     * @return  self
     */
    public function setSelectedText($selectedText)
    {
        $this->selectedText = $selectedText;

        return $this;
    }

    /**
     * Get the value of themeColour
     */
    public function getThemeColour()
    {
        return $this->themeColour;
    }

    /**
     * Set the value of themeColour
     *
     * @return  self
     */
    public function setThemeColour($themeColour)
    {
        $this->themeColour = $themeColour;

        return $this;
    }

    /**
     * Get the value of backgroundColour
     */
    public function getBackgroundColour()
    {
        return $this->backgroundColour;
    }

    /**
     * Set the value of backgroundColour
     *
     * @return  self
     */
    public function setBackgroundColour($backgroundColour)
    {
        $this->backgroundColour = $backgroundColour;

        return $this;
    }

    /**
     * Get the value of textColour
     */
    public function getTextColour()
    {
        return $this->textColour;
    }

    /**
     * Set the value of textColour
     *
     * @return  self
     */
    public function setTextColour($textColour)
    {
        $this->textColour = $textColour;

        return $this;
    }

    /**
     * Get the value of headerImageType
     */
    public function getHeaderImageType()
    {
        return $this->headerImageType;
    }

    /**
     * Set the value of headerImageType
     *
     * @return  self
     */
    public function setHeaderImageType($headerImageType)
    {
        $this->headerImageType = $headerImageType;

        return $this;
    }

    /**
     * Get the value of headerImage
     */
    public function getHeaderImage()
    {
        return $this->headerImage;
    }

    /**
     * Set the value of headerImage
     *
     * @return  self
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = $headerImage;

        return $this;
    }

    /**
     * Get the value of hamburgerImage
     */
    public function getHamburgerImage()
    {
        return $this->hamburgerImage;
    }

    /**
     * Set the value of hamburgerImage
     *
     * @return  self
     */
    public function setHamburgerImage($hamburgerImage)
    {
        $this->hamburgerImage = $hamburgerImage;

        return $this;
    }

    /**
     * Get the value of loadingSpinner
     */
    public function getLoadingSpinner()
    {
        return $this->loadingSpinner;
    }

    /**
     * Set the value of loadingSpinner
     *
     * @return  self
     */
    public function setLoadingSpinner($loadingSpinner)
    {
        $this->loadingSpinner = $loadingSpinner;

        return $this;
    }

    /**
     * Get the value of menuSlideOutWidth
     */
    public function getMenuSlideOutWidth()
    {
        return $this->menuSlideOutWidth;
    }

    /**
     * Set the value of menuSlideOutWidth
     *
     * @return  self
     */
    public function setMenuSlideOutWidth($menuSlideOutWidth)
    {
        $this->menuSlideOutWidth = $menuSlideOutWidth;

        return $this;
    }

    /**
     * Get the value of DFTNetworkId
     */
    public function getDFTNetworkId()
    {
        return $this->DFTNetworkId;
    }

    /**
     * Set the value of DFTNetworkId
     *
     * @return  self
     */
    public function setDFTNetworkId($DFTNetworkId)
    {
        $this->DFTNetworkId = $DFTNetworkId;

        return $this;
    }

    /**
     * Get the value of AdUnit
     */
    public function getAdUnit()
    {
        return $this->AdUnit;
    }

    /**
     * Set the value of AdUnit
     *
     * @return  self
     */
    public function setAdUnit($AdUnit)
    {
        $this->AdUnit = $AdUnit;

        return $this;
    }

    /**
     * Get the value of AdUnitSectionExtended
     */
    public function getAdUnitSectionExtended()
    {
        return $this->AdUnitSectionExtended;
    }

    /**
     * Set the value of AdUnitSectionExtended
     *
     * @return  self
     */
    public function setAdUnitSectionExtended($AdUnitSectionExtended)
    {
        $this->AdUnitSectionExtended = $AdUnitSectionExtended;

        return $this;
    }

    /**
     * Get the value of GATrackingCode
     */
    public function getGATrackingCode()
    {
        return $this->GATrackingCode;
    }

    /**
     * Set the value of GATrackingCode
     *
     * @return  self
     */
    public function setGATrackingCode($GATrackingCode)
    {
        $this->GATrackingCode = $GATrackingCode;

        return $this;
    }

    /**
     * Get the value of GTMID
     */
    public function getGTMID()
    {
        return $this->GTMID;
    }

    /**
     * Set the value of GTMID
     *
     * @return  self
     */
    public function setGTMID($GTMID)
    {
        $this->GTMID = $GTMID;

        return $this;
    }

    /**
     * Get the value of sectionSliderTextColor
     */
    public function getSectionSliderTextColor()
    {
        return $this->sectionSliderTextColor;
    }

    /**
     * Set the value of sectionSliderTextColor
     *
     * @return  self
     */
    public function setSectionSliderTextColor($sectionSliderTextColor)
    {
        $this->sectionSliderTextColor = $sectionSliderTextColor;

        return $this;
    }

    /**
     * Get the value of sectionSliderBackground
     */
    public function getSectionSliderBackground()
    {
        return $this->sectionSliderBackground;
    }

    /**
     * Set the value of sectionSliderBackground
     *
     * @return  self
     */
    public function setSectionSliderBackground($sectionSliderBackground)
    {
        $this->sectionSliderBackground = $sectionSliderBackground;

        return $this;
    }

    /**
     * Get the value of listAdInterval
     */
    public function getListAdInterval()
    {
        return $this->listAdInterval;
    }

    /**
     * Set the value of listAdInterval
     *
     * @return  self
     */
    public function setListAdInterval($listAdInterval)
    {
        $this->listAdInterval = $listAdInterval;

        return $this;
    }

    /**
     * Get the value of listAdMax
     */
    public function getListAdMax()
    {
        return $this->listAdMax;
    }

    /**
     * Set the value of listAdMax
     *
     * @return  self
     */
    public function setListAdMax($listAdMax)
    {
        $this->listAdMax = $listAdMax;

        return $this;
    }

    /**
     * Get the value of highlightsColour
     */
    public function getHighlightsColour()
    {
        return $this->highlightsColour;
    }

    /**
     * Set the value of highlightsColour
     *
     * @return  self
     */
    public function setHighlightsColour($highlightsColour)
    {
        $this->highlightsColour = $highlightsColour;

        return $this;
    }

    /**
     * Get the value of borderColour
     */
    public function getBorderColour()
    {
        return $this->borderColour;
    }

    /**
     * Set the value of borderColour
     *
     * @return  self
     */
    public function setBorderColour($borderColour)
    {
        $this->borderColour = $borderColour;

        return $this;
    }

    /**
     * Get the value of sectionDownloadEnabled
     */
    public function getSectionDownloadEnabled()
    {
        return $this->sectionDownloadEnabled;
    }

    /**
     * Set the value of sectionDownloadEnabled
     *
     * @return  self
     */
    public function setSectionDownloadEnabled($sectionDownloadEnabled)
    {
        $this->sectionDownloadEnabled = $sectionDownloadEnabled;

        return $this;
    }

    /**
     * Get the value of routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Set the value of routes
     *
     * @return  self
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * Get the value of layout
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set the value of layout
     *
     * @return  self
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get the value of multiSection
     */
    public function getMultiSection()
    {
        return $this->multiSection;
    }

    /**
     * Set the value of multiSection
     *
     * @return  self
     */
    public function setMultiSection($multiSection)
    {
        $this->multiSection = $multiSection;

        return $this;
    }

    /**
     * Get the value of flattenSections
     */
    public function getFlattenSections()
    {
        return $this->flattenSections;
    }

    /**
     * Set the value of flattenSections
     *
     * @return  self
     */
    public function setFlattenSections($flattenSections)
    {
        $this->flattenSections = $flattenSections;

        return $this;
    }

    /**
     * Get the value of serviceWorkerUrl
     */
    public function getServiceWorkerUrl()
    {
        return $this->serviceWorkerUrl;
    }

    /**
     * Set the value of serviceWorkerUrl
     *
     * @return  self
     */
    public function setServiceWorkerUrl($serviceWorkerUrl)
    {
        $this->serviceWorkerUrl = $serviceWorkerUrl;

        return $this;
    }

    /**
     * Get the value of showDateBlockOnFeedListItem
     */
    public function getShowDateBlockOnFeedListItem()
    {
        return $this->showDateBlockOnFeedListItem;
    }

    /**
     * Set the value of showDateBlockOnFeedListItem
     *
     * @return  self
     */
    public function setShowDateBlockOnFeedListItem($showDateBlockOnFeedListItem)
    {
        $this->showDateBlockOnFeedListItem = $showDateBlockOnFeedListItem;

        return $this;
    }

    /**
     * Get the value of showAllFeed
     */
    public function getShowAllFeed()
    {
        return $this->showAllFeed;
    }

    /**
     * Set the value of showAllFeed
     *
     * @return  self
     */
    public function setShowAllFeed($showAllFeed)
    {
        $this->showAllFeed = $showAllFeed;

        return $this;
    }

    /**
     * Get the value of imageGalleryHeight
     */
    public function getImageGalleryHeight()
    {
        return $this->imageGalleryHeight;
    }

    /**
     * Set the value of imageGalleryHeight
     *
     * @return  self
     */
    public function setImageGalleryHeight($imageGalleryHeight)
    {
        $this->imageGalleryHeight = $imageGalleryHeight;

        return $this;
    }

    /**
     * Get the value of mastHeadHeight
     */
    public function getMastHeadHeight()
    {
        return $this->mastHeadHeight;
    }

    /**
     * Set the value of mastHeadHeight
     *
     * @return  self
     */
    public function setMastHeadHeight($mastHeadHeight)
    {
        $this->mastHeadHeight = $mastHeadHeight;

        return $this;
    }

    /**
     * Get the value of searchLightTheme
     */
    public function getSearchLightTheme()
    {
        return $this->searchLightTheme;
    }

    /**
     * Set the value of searchLightTheme
     *
     * @return  self
     */
    public function setSearchLightTheme($searchLightTheme)
    {
        $this->searchLightTheme = $searchLightTheme;

        return $this;
    }

    /**
     * Get the value of showDatesOnList
     */
    public function getShowDatesOnList()
    {
        return $this->showDatesOnList;
    }

    /**
     * Set the value of showDatesOnList
     *
     * @return  self
     */
    public function setShowDatesOnList($showDatesOnList)
    {
        $this->showDatesOnList = $showDatesOnList;

        return $this;
    }

    /**
     * Get the value of showSearch
     */
    public function getShowSearch()
    {
        return $this->showSearch;
    }

    /**
     * Set the value of showSearch
     *
     * @return  self
     */
    public function setShowSearch($showSearch)
    {
        $this->showSearch = $showSearch;

        return $this;
    }

    /**
     * Get the value of searchParam
     */
    public function getSearchParam()
    {
        return $this->searchParam;
    }

    /**
     * Set the value of searchParam
     *
     * @return  self
     */
    public function setSearchParam($searchParam)
    {
        $this->searchParam = $searchParam;

        return $this;
    }

    /**
     * Get the value of searchAction
     */
    public function getSearchAction()
    {
        return $this->searchAction;
    }

    /**
     * Set the value of searchAction
     *
     * @return  self
     */
    public function setSearchAction($searchAction)
    {
        $this->searchAction = $searchAction;

        return $this;
    }

    /**
     * Get the value of maxWidth
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * Set the value of maxWidth
     *
     * @return  self
     */
    public function setMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;

        return $this;
    }

    /**
     * Get the value of priorityTags
     */
    public function getPriorityTags()
    {
        return $this->priorityTags;
    }

    /**
     * Set the value of priorityTags
     *
     * @return  self
     */
    public function setPriorityTags($priorityTags)
    {
        $this->priorityTags = $priorityTags;

        return $this;
    }

    /**
     * Get the value of tagConfig
     */
    public function getTagConfig()
    {
        return $this->tagConfig;
    }

    /**
     * Set the value of tagConfig
     *
     * @return  self
     */
    public function setTagConfig($tagConfig)
    {
        $this->tagConfig = $tagConfig;

        return $this;
    }

    /**
     * Get the value of topHeros
     */
    public function getTopHeros()
    {
        return $this->topHeros;
    }

    /**
     * Set the value of topHeros
     *
     * @return  self
     */
    public function setTopHeros($topHeros)
    {
        $this->topHeros = $topHeros;

        return $this;
    }

    /**
     * Get the value of addThisCode
     */
    public function getAddThisCode()
    {
        return $this->addThisCode;
    }

    /**
     * Set the value of addThisCode
     *
     * @return  self
     */
    public function setAddThisCode($addThisCode)
    {
        $this->addThisCode = $addThisCode;

        return $this;
    }

    /**
     * Get the value of socialShareKitButtons
     */
    public function getSocialShareKitButtons()
    {
        return $this->socialShareKitButtons;
    }

    /**
     * Set the value of socialShareKitButtons
     *
     * @return  self
     */
    public function setSocialShareKitButtons($socialShareKitButtons)
    {
        $this->socialShareKitButtons = $socialShareKitButtons;

        return $this;
    }

    /**
     * Get the value of extraLinks
     */
    public function getExtraLinks()
    {
        return $this->extraLinks;
    }

    /**
     * Set the value of extraLinks
     *
     * @return  self
     */
    public function setExtraLinks($extraLinks)
    {
        $this->extraLinks = $extraLinks;

        return $this;
    }


    /**
     * Get the value of twitterEmbedUrl
     */ 
    public function getTwitterEmbedUrl()
    {
        return $this->twitterEmbedUrl;
    }

    /**
     * Set the value of twitterEmbedUrl
     *
     * @return  self
     */ 
    public function setTwitterEmbedUrl($twitterEmbedUrl)
    {
        $this->twitterEmbedUrl = $twitterEmbedUrl;

        return $this;
    }

    /**
     * Get the value of instagramEmbedUrl
     */ 
    public function getInstagramEmbedUrl()
    {
        return $this->instagramEmbedUrl;
    }

    /**
     * Set the value of instagramEmbedUrl
     *
     * @return  self
     */ 
    public function setInstagramEmbedUrl($instagramEmbedUrl)
    {
        $this->instagramEmbedUrl = $instagramEmbedUrl;

        return $this;
    }

    /**
     * Get the value of shareTitlePrefix
     */ 
    public function getShareTitlePrefix()
    {
        return $this->shareTitlePrefix;
    }

    /**
     * Set the value of shareTitlePrefix
     *
     * @return  self
     */ 
    public function setShareTitlePrefix($shareTitlePrefix)
    {
        $this->shareTitlePrefix = $shareTitlePrefix;

        return $this;
    }

    /**
     * Get the value of customStyles
     */ 
    public function getCustomStyles()
    {
        return $this->customStyles;
    }

    /**
     * Set the value of customStyles
     *
     * @return  self
     */ 
    public function setCustomStyles($customStyles)
    {
        $this->customStyles = $customStyles;

        return $this;
    }

    /**
     * Get the value of moreBlockTags
     */ 
    public function getMoreBlockTags()
    {
        return $this->moreBlockTags;
    }

    /**
     * Set the value of moreBlockTags
     *
     * @return  self
     */ 
    public function setMoreBlockTags($moreBlockTags)
    {
        $this->moreBlockTags = $moreBlockTags;

        return $this;
    }

    /**
     * Get the value of hamburgerImageMarginTop
     */ 
    public function getHamburgerImageMarginTop()
    {
        return $this->hamburgerImageMarginTop;
    }

    /**
     * Set the value of hamburgerImageMarginTop
     *
     * @return  self
     */ 
    public function setHamburgerImageMarginTop($hamburgerImageMarginTop)
    {
        $this->hamburgerImageMarginTop = $hamburgerImageMarginTop;

        return $this;
    }

    /**
     * Get the value of customHtml
     */ 
    public function getCustomHtml()
    {
        return $this->customHtml;
    }

    /**
     * Set the value of customHtml
     *
     * @return  self
     */ 
    public function setCustomHtml($customHtml)
    {
        $this->customHtml = $customHtml;

        return $this;
    }

    /**
     * Get the value of infiniteVerticalArticleScroll
     */ 
    public function getInfiniteVerticalArticleScroll()
    {
        return $this->infiniteVerticalArticleScroll;
    }

    /**
     * Set the value of infiniteVerticalArticleScroll
     *
     * @return  self
     */ 
    public function setInfiniteVerticalArticleScroll($infiniteVerticalArticleScroll)
    {
        $this->infiniteVerticalArticleScroll = $infiniteVerticalArticleScroll;

        return $this;
    }

    /**
     * Get the value of infiniteHorizontalArticleScroll
     */ 
    public function getInfiniteHorizontalArticleScroll()
    {
        return $this->infiniteHorizontalArticleScroll;
    }

    /**
     * Set the value of infiniteHorizontalArticleScroll
     *
     * @return  self
     */ 
    public function setInfiniteHorizontalArticleScroll($infiniteHorizontalArticleScroll)
    {
        $this->infiniteHorizontalArticleScroll = $infiniteHorizontalArticleScroll;

        return $this;
    }

    /**
     * Get the value of remote
     */ 
    public function getRemote()
    {
        return $this->remote;
    }

    /**
     * Set the value of remote
     *
     * @return  self
     */ 
    public function setRemote($remote)
    {
        $this->remote = $remote;

        return $this;
    }

    /**
     * Get the value of excludedSections
     */ 
    public function getExcludedSections()
    {
        return $this->excludedSections;
    }

    /**
     * Set the value of excludedSections
     *
     * @return  self
     */ 
    public function setExcludedSections($excludedSections)
    {
        $this->excludedSections = $excludedSections;

        return $this;
    }

    /**
     * Get the value of appEndpoint
     */ 
    public function getAppEndpoint()
    {
        return $this->appEndpoint;
    }

    /**
     * Set the value of appEndpoint
     *
     * @return  self
     */ 
    public function setAppEndpoint($appEndpoint)
    {
        $this->appEndpoint = $appEndpoint;

        return $this;
    }

    /**
     * Get the value of showClassicSwitch
     */ 
    public function getShowClassicSwitch()
    {
        return $this->showClassicSwitch;
    }

    /**
     * Set the value of showClassicSwitch
     *
     * @return  self
     */ 
    public function setShowClassicSwitch($showClassicSwitch)
    {
        $this->showClassicSwitch = $showClassicSwitch;

        return $this;
    }

    /**
     * Get the value of sectionPrefix
     */ 
    public function getSectionPrefix()
    {
        return $this->sectionPrefix;
    }

    /**
     * Set the value of sectionPrefix
     *
     * @return  self
     */ 
    public function setSectionPrefix($sectionPrefix)
    {
        $this->sectionPrefix = $sectionPrefix;

        return $this;
    }

    /**
     * Get the value of dnsPrefetch
     */ 
    public function getDnsPrefetch()
    {
        return $this->dnsPrefetch;
    }

    /**
     * Set the value of dnsPrefetch
     *
     * @return  self
     */ 
    public function setDnsPrefetch($dnsPrefetch)
    {
        $this->dnsPrefetch = $dnsPrefetch;

        return $this;
    }

    /**
     * Get the value of apiEndpoint
     */ 
    public function getApiEndpoint()
    {
        return $this->apiEndPoint;
    }

    /**
     * Set the value of apiEndPoint
     *
     * @return  self
     */ 
    public function setApiEndpoint($apiEndpoint)
    {
        $this->apiEndPoint = $apiEndpoint;

        return $this;
    }

    /**
     * Get the value of renderAds
     */ 
    public function getRenderAds()
    {
        return $this->renderAds;
    }

    /**
     * Set the value of renderAds
     *
     * @return self
     */ 
    public function setRenderAds($renderAds)
    {
        $this->renderAds = $renderAds;

        return $this;
    }

    /**
     * Get the value of renderAdsServerSide
     */ 
    public function getRenderAdsServerSide()
    {
        return $this->renderAdsServerSide;
    }

    /**
     * Set the value of renderAdsServerSide
     *
     * @return self
     */ 
    public function setRenderAdsServerSide($renderAdsServerSide)
    {
        $this->renderAdsServerSide = $renderAdsServerSide;

        return $this;
    }

    /**
     * Get the value of hasTaboola
     */ 
    public function getHasTaboola()
    {
        return $this->hasTaboola;
    }

    /**
     * Set the value of hasTaboola
     *
     * @return self
     */ 
    public function setHasTaboola($hasTaboola)
    {
        $this->hasTaboola = $hasTaboola;

        return $this;
    }

    /**
     * Get the value of firstImpressionsId
     */ 
    public function getFirstImpressionsId()
    {
        return $this->firstImpressionsId;
    }

    /**
     * Set the value of firstImpressionsId
     *
     * @return self
     */ 
    public function setFirstImpressionsId($firstImpressionsId)
    {
        $this->firstImpressionsId = $firstImpressionsId;

        return $this;
    }
    
    /**
     * Get the value of whitelistedSections
     */ 
    public function getWhitelistedSections()
    {
        return $this->whitelistedSections;
    }

    /**
     * Set the value of whitelistedSections
     *
     * @return self
     */ 
    public function setWhitelistedSections($whitelistedSections)
    {
        $this->whitelistedSections = $whitelistedSections;

        return $this;
    }

    /**
     * Get the value of instagramSocialUrl
     */ 
    public function getInstagramSocialUrl()
    {
        return $this->instagramSocialUrl;
    }

    /**
     * Set the value of instagramSocialUrl
     *
     * @return self
     */ 
    public function setInstagramSocialUrl($instagramSocialUrl)
    {
        $this->instagramSocialUrl = $instagramSocialUrl;

        return $this;
    }

    /**
     * Get the value of twitterSocialUrl
     */ 
    public function getTwitterSocialUrl()
    {
        return $this->twitterSocialUrl;
    }

    /**
     * Set the value of twitterSocialUrl
     *
     * @return self
     */ 
    public function setTwitterSocialUrl($twitterSocialUrl)
    {
        $this->twitterSocialUrl = $twitterSocialUrl;

        return $this;
    }

    /**
     * Get the value of facebookSocialUrl
     */ 
    public function getFacebookSocialUrl()
    {
        return $this->facebookSocialUrl;
    }

    /**
     * Set the value of facebookSocialUrl
     *
     * @return self
     */ 
    public function setFacebookSocialUrl($facebookSocialUrl)
    {
        $this->facebookSocialUrl = $facebookSocialUrl;

        return $this;
    }

    /**
     * Get the value of youtubeSocialUrl
     */ 
    public function getYoutubeSocialUrl()
    {
        return $this->youtubeSocialUrl;
    }

    /**
     * Set the value of youtubeSocialUrl
     *
     * @return self
     */ 
    public function setYoutubeSocialUrl($youtubeSocialUrl)
    {
        $this->youtubeSocialUrl = $youtubeSocialUrl;

        return $this;
    }
}