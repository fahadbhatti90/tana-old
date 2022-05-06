<?php
return [
    'delayTimeInApi' => 3000, // The number of milliseconds to delay, URL::http://docs.guzzlephp.org/en/stable/request-options.html#delay
    'connectTimeOutInApi' => 30, // Timeout if the client fails to connect to the server, URL::http://docs.guzzlephp.org/en/stable/request-options.html#connect-timeout
    'timeoutInApi' => 60, // Timeout if a server does not return a response . http://docs.guzzlephp.org/en/stable/request-options.html#timeout
    'sleepTime' => 3,
    'apiVersion' => 'v2',
    'nextDateTimeFormat' => date('Y-m-d H:i:s', strtotime('+1 day', time())),
    'lastDateTimeFormat' => date('Y-m-d H:i:s', strtotime('-1 day', time())),
    'dateTimeFormat' => date('Y-m-d H:i:s'),
    'refresh_token' => 'Atzr|IwEBIEql2WtnvLUUdyRxDFHfCyLzSPx2yl_M-kpBLsOcwPJUnbUqo4ROr0BT62SSAdaEFGMRq7JVQ7B2Kb-RrtUnR0ycW0OUjtp-X4XGb91FYP1i4Pe3bkHaqQdoZRLf7Kp4awIqzVpAxkQerNPkLn6XJCmNmkr0sEDZYz7gMsTcZ3h5fgkD4fJY0lsuSRdFLI4CMi_bZHXabciQtKcSRu5ADuL3ZA4GxIC5YR_2or2HOXGBp7tRPM9XqnQAqq-WW660RumhzvIHvZmYDwMqcWwNM_6yvmoEJzjwxaXq3O8DgoeDMP-mzQT0bphWLLrYoRlx1eg41s7vtjod79C8eg10lrca3SK4gvfp0VKqHaERIJoaGtfj-zsKq2TISQjTd2BNzXtPFawn79KEIadaPAhbB3izF9T1DK4uhrH51TQkds4HA0_yVi5WC3aRXx4_I0Ea5hPGi2BCyzVDtWDI3hMrrkrxG3gAs_dFC5RboD-pbkCPL_MAORNb6_UpkzxrfhSxFJ4zBQOdp9-q-d1HD36v9kWZHOcYoc7NFXSlrhs-M2XY1yKwa1lx0_Ba8kB7Yf_jXHI',
    'ReportDate' => date_format(date_sub(date_create(date("Ymd")), date_interval_create_from_date_string("1 day")), "Ymd"),
    'dayFormat' => date('Y-m-d'),
    'amsApiUrl' => 'https://advertising-api.amazon.com',
    'testingAmsApiUrl' => 'https://advertising-api-test.amazon.com',
    'amsAuthUrl' => 'https://api.amazon.com/auth/o2/token',
    'amsProfileUrl' => 'profiles',
    'amshsaCampaignUrl' => 'hsa/campaigns',
    'SPCampaignReport' => 'sp/campaigns/report',
    'SDCampaignReport' => 'sd/campaigns/report',
    'SDproductAdsReport' => 'sd/productAds/report',
    'SDadGroupsReport' => 'sd/adGroups/report',
    'HSACampaignReport' => 'hsa/campaigns/report',
    'HSAKeywordReport' => 'hsa/keywords/report',
    'spKeywordList' => 'sp/keywords/extended',
    'sbKeywordList' => 'sb/keywords',
    'sdTargetsList' => 'sd/targets',
    'spKeywordReport' => 'sp/keywords/report',
    'productAdsReport' => 'sp/productAds/report',
    'targetsReport' => 'sp/targets/report',
    'targetsReportSb' => 'hsa/targets/report',
    'adGroupsReport' => 'sp/adGroups/report',
    'adGroupsReportSb' => 'hsa/adGroups/report',
    'ASINsReport' => 'asins/report',
    'downloadReport' => 'reports',
    'amsPortfolioUrl' => 'portfolios',
    'spCampaignUrl' => 'sp/campaigns',
    'sdCampaignUrl' => 'sd/campaigns',
    'sbCampaignUrl' => 'sb/campaigns',
    'portfolioSponsoredBrand' => 'sponsoredBrand',
    'portfolioSponsoredDisplay' => 'sponsoredDisplay',
    'portfolioSponsoredProduct' => 'sponsoredProducts',
    // Sponsored Products Campaign Metrics List
    'spCampaignMetrics' => 'bidPlus,campaignName,campaignId,campaignStatus,campaignBudget,impressions,clicks,cost,portfolioId,portfolioName,attributedConversions1d,attributedConversions7d,attributedConversions14d,attributedConversions30d,attributedConversions1dSameSKU,attributedConversions7dSameSKU,attributedConversions14dSameSKU,attributedConversions30dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered7d,attributedUnitsOrdered14d,attributedUnitsOrdered30d,attributedSales1d,attributedSales7d,attributedSales14d,attributedSales30d,attributedSales1dSameSKU,attributedSales7dSameSKU,attributedSales14dSameSKU,attributedSales30dSameSKU,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered7dSameSKU,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered30dSameSKU',
    // Sponsored Products AdGroup Metrics List
    'spAdGroupMetrics' => 'campaignName,campaignId,adGroupName,adGroupId,impressions,clicks,cost,attributedConversions1d,attributedConversions7d,attributedConversions14d,attributedConversions30d,attributedConversions1dSameSKU,attributedConversions7dSameSKU,attributedConversions14dSameSKU,attributedConversions30dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered7d,attributedUnitsOrdered14d,attributedUnitsOrdered30d,attributedSales1d,attributedSales7d,attributedSales14d,attributedSales30d,attributedSales1dSameSKU,attributedSales7dSameSKU,attributedSales14dSameSKU,attributedSales30dSameSKU',
    // Sponsored Brands Keyword Metrics List
    'sbKeywordMetrics' => 'campaignName,campaignId,campaignStatus,campaignBudget,campaignBudgetType,adGroupName,adGroupId,keywordId,keywordText,matchType,impressions,clicks,cost,attributedSales14d,attributedSales14dSameSKU,attributedConversions14d,attributedConversions14dSameSKU,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedOrderRateNewToBrand14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,keywordBid,keywordStatus,targetId,targetingExpression,targetingText,targetingType,attributedDetailPageViewsClicks14d,unitsSold14d,dpv14d',
    // Sponsored Products Product Targeting Metrics List
    'productTargetingMetrics' => 'campaignName,campaignId,targetId,targetingExpression,targetingText,targetingType,impressions,clicks,cost,attributedConversions1d,attributedConversions7d,attributedConversions14d,attributedConversions30d,attributedConversions1dSameSKU,attributedConversions7dSameSKU,attributedConversions14dSameSKU,attributedConversions30dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered7d,attributedUnitsOrdered14d,attributedUnitsOrdered30d,attributedSales1d,attributedSales7d,attributedSales14d,attributedSales30d,attributedSales1dSameSKU,attributedSales7dSameSKU,attributedSales14dSameSKU,attributedSales30dSameSKU',
    // Sponsored Products Products Ads Metrics List
    'productAdsMetrics' => 'campaignName,campaignId,adGroupName,adGroupId,impressions,clicks,cost,currency,asin,attributedConversions1d,attributedConversions7d,attributedConversions14d,attributedConversions30d,attributedConversions1dSameSKU,attributedConversions7dSameSKU,attributedConversions14dSameSKU,attributedConversions30dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered7d,attributedUnitsOrdered14d,attributedUnitsOrdered30d,attributedSales1d,attributedSales7d,attributedSales14d,attributedSales30d,attributedSales1dSameSKU,attributedSales7dSameSKU,attributedSales14dSameSKU,attributedSales30dSameSKU,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered7dSameSKU,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered30dSameSKU',
    /****************************************************************
     * Sponsored Products Products Ads Metrics List With SKU Field
     * **************************************************************/
    'productAdsMetricsSKU' => 'campaignName,campaignId,adGroupName,adGroupId,impressions,clicks,cost,currency,asin,sku,attributedConversions1d,attributedConversions7d,attributedConversions14d,attributedConversions30d,attributedConversions1dSameSKU,attributedConversions7dSameSKU,attributedConversions14dSameSKU,attributedConversions30dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered7d,attributedUnitsOrdered14d,attributedUnitsOrdered30d,attributedSales1d,attributedSales7d,attributedSales14d,attributedSales30d,attributedSales1dSameSKU,attributedSales7dSameSKU,attributedSales14dSameSKU,attributedSales30dSameSKU,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered7dSameSKU,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered30dSameSKU',
    // Sponsored Products Keyword Metrics List
    'spKeywordMetrics' => 'campaignName,campaignId,adGroupName,adGroupId,keywordId,keywordText,matchType,impressions,clicks,cost,attributedConversions1d,attributedConversions7d,attributedConversions14d,attributedConversions30d,attributedConversions1dSameSKU,attributedConversions7dSameSKU,attributedConversions14dSameSKU,attributedConversions30dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered7d,attributedUnitsOrdered14d,attributedUnitsOrdered30d,attributedSales1d,attributedSales7d,attributedSales14d,attributedSales30d,attributedSales1dSameSKU,attributedSales7dSameSKU,attributedSales14dSameSKU,attributedSales30dSameSKU',
    // Sponsored Products ASIN Reports
    'asinsReportsMetrics' => 'campaignName,campaignId,adGroupName,adGroupId,keywordId,keywordText,asin,otherAsin,currency,matchType,attributedUnitsOrdered1d,attributedUnitsOrdered7d,attributedUnitsOrdered14d,attributedUnitsOrdered30d,attributedUnitsOrdered1dOtherSKU,attributedUnitsOrdered7dOtherSKU,attributedUnitsOrdered14dOtherSKU,attributedUnitsOrdered30dOtherSKU,attributedSales1dOtherSKU,attributedSales7dOtherSKU,attributedSales14dOtherSKU,attributedSales30dOtherSKU',
    /****************************************************************
     * Sponsored Products ASIN Reports Metrics List With SKU Field
     * **************************************************************/
    'asinsReportsMetricsSKU' => 'campaignName,campaignId,adGroupName,adGroupId,keywordId,keywordText,asin,otherAsin,sku,currency,matchType,attributedUnitsOrdered1d,attributedUnitsOrdered7d,attributedUnitsOrdered14d,attributedUnitsOrdered30d,attributedUnitsOrdered1dOtherSKU,attributedUnitsOrdered7dOtherSKU,attributedUnitsOrdered14dOtherSKU,attributedUnitsOrdered30dOtherSKU,attributedSales1dOtherSKU,attributedSales7dOtherSKU,attributedSales14dOtherSKU,attributedSales30dOtherSKU',
    /****************************************************************
     * Sponsored Brand Campaign Reports Metrics List
     * **************************************************************/
    'sbCampaignMetrics' => 'campaignName,campaignId,campaignStatus,campaignBudget,campaignBudgetType,impressions,clicks,cost,attributedDetailPageViewsClicks14d,attributedSales14d,attributedSales14dSameSKU,attributedConversions14d,attributedConversions14dSameSKU,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedOrderRateNewToBrand14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,unitsSold14d,dpv14d',
    /****************************************************************
     * Sponsored Display Campaign Reports Metrics List
     * **************************************************************/
    'sdCampaignMetrics' => 'campaignName,campaignId,campaignStatus,currency,impressions,clicks,cost,attributedDPV14d,attributedUnitsSold14d,attributedSales14d',
    /****************************************************************
    * Sponsored Display productAds Reports Metrics List
     * **************************************************************/
    'sdProductAdsMetrics' => 'adGroupName,adGroupId,asin,sku,campaignName,campaignId,impressions,clicks,cost,currency,attributedConversions1d,attributedConversions7d,attributedConversions14d,attributedConversions30d,attributedConversions1dSameSKU,attributedConversions7dSameSKU,attributedConversions14dSameSKU,attributedConversions30dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered7d,attributedUnitsOrdered14d,attributedUnitsOrdered30d,attributedSales1d,attributedSales7d,attributedSales14d,attributedSales30d,attributedSales1dSameSKU,attributedSales7dSameSKU,attributedSales14dSameSKU,attributedSales30dSameSKU',
    /****************************************************************
    * Sponsored Display AdGroup Reports Metrics List
    * **************************************************************/
    'sdAdGroupMetrics' => 'adGroupName,adGroupId,campaignName,campaignId,impressions,clicks,cost,currency,attributedConversions1d,attributedConversions7d,attributedConversions14d,attributedConversions30d,attributedConversions1dSameSKU,attributedConversions7dSameSKU,attributedConversions14dSameSKU,attributedConversions30dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered7d,attributedUnitsOrdered14d,attributedUnitsOrdered30d,attributedSales1d,attributedSales7d,attributedSales14d,attributedSales30d,attributedSales1dSameSKU,attributedSales7dSameSKU,attributedSales14dSameSKU,attributedSales30dSameSKU',
    /****************************************************************
     * Sponsored Brand AdGroup Reports Metrics List
     * **************************************************************/
    'sbAdGroupMetrics' => 'campaignId,campaignName,campaignBudget,campaignBudgetType,campaignStatus,adGroupName,adGroupId,impressions,clicks,cost,attributedDetailPageViewsClicks14d,attributedSales14d,attributedSales14dSameSKU,attributedConversions14d,attributedConversions14dSameSKU,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedOrderRateNewToBrand14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,unitsSold14d,dpv14d',
    /****************************************************************
     * Sponsored Brand Targeting Reports Metrics List
     * **************************************************************/
    'sbTargetingMetrics' => 'campaignId,campaignName,adGroupId,adGroupName,campaignBudgetType,campaignStatus,targetId,targetingExpression,targetingType,targetingText,impressions,clicks,cost,attributedDetailPageViewsClicks14d,attributedSales14d,attributedSales14dSameSKU,attributedConversions14d,attributedConversions14dSameSKU,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedOrderRateNewToBrand14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,unitsSold14d,dpv14d',
    //------------------------------------------------------------------------------------
];