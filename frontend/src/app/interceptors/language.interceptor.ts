import { HttpInterceptorFn } from '@angular/common/http';

export const languageInterceptor: HttpInterceptorFn = (req, next) => {
  const clonedRequest = req.clone({
    setHeaders: {
      'Accept-language':'es'
    }
  });
  return next(clonedRequest);
};
