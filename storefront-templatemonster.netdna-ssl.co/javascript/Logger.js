try {
  function Logger() {
    this.oldError = window.onerror;
    this.oldUnhandledRejection = window.onunhandledrejection;

    for (const name of Object.getOwnPropertyNames(Object.getPrototypeOf(this))) {
      const method = this[name];
      if (name !== 'constructor' && typeof method === 'function') {
        this[name] = method.bind(this);
      }
    }

    window.dataLayer = window.dataLayer || [];
    window.addEventListener('error', this.error);
    window.addEventListener('unhandledrejection', this.unhandledrejection);
  };

  Logger.prototype.error = function(error) {
    // if (!isProduction) console.dir(error);
    if (this.oldError) this.oldError.apply(this, error);
    this.report('JS Error', {
      message: error.message,
      name: error.name,
      type: error.type,
      statusCode: error.statusCode,
      statusText: error.statusText,
      fileName: error.fileName,
      lineNumber: error.lineNumber,
      columnNumber: error.columnNumber,
      stack: error.stack,
    });
    return false;
  };

  Logger.prototype.unhandledrejection = function(error) {
    // if (!isProduction) console.dir(error);
    if (this.oldUnhandledRejection) this.oldUnhandledRejection.apply(this, arguments);
    this.report('JS Unhandled Rejection', {
      reason: error.reason,
      message: error.message,
      name: error.name,
      type: error.type,
      statusCode: error.statusCode,
      statusText: error.statusText,
      fileName: error.fileName,
      lineNumber: error.lineNumber,
      columnNumber: error.columnNumber,
      stack: error.stack,
    });
    return false;
  };

  Logger.prototype.didCatch = function(error, info, props) {
    // if (!isProduction) console.dir(error);
    this.report('React Component Error', {
      error: {
        message: error.message,
        name: error.name,
        type: error.type,
        statusCode: error.statusCode,
        statusText: error.statusText,
        fileName: error.fileName,
        lineNumber: error.lineNumber,
        columnNumber: error.columnNumber,
        stack: error.stack,
      }, info, props });
  };

  Logger.prototype.action = function(error) {
    // if (!isProduction) console.dir(error);
    this.report('React Action Error', {
      message: error.message,
      name: error.name,
      type: error.type,
      statusCode: error.statusCode,
      statusText: error.statusText,
      fileName: error.fileName,
      lineNumber: error.lineNumber,
      columnNumber: error.columnNumber,
      stack: error.stack,
    });
  };

  Logger.prototype.report = function(category, data) {
    // window.dataLayer.push({event: 'error', category: category, data});
    // TODO: add error log
  };

  window.Logger = new Logger();
} catch (error) {} // eslint-disable-line
