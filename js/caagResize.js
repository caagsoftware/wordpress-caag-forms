var iframes = iFrameResize({
        log:false,
        checkOrigin: false,
        maxWidth: screen.width,
        maxHeight: screen.height,
        minWidth: 400,
        sizeWidth: true,
        autoResize: true,
        bodyMargin: 'none',
        heightCalculationMethod: 'max'
},'#caag-iframe' );
