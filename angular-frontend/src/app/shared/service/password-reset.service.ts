import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { catchError, Observable, throwError } from 'rxjs';
import { AUTH_ROUTES } from '../routes/auth-routes';

@Injectable({
  providedIn: 'root'
})
export class PasswordResetService {

  constructor(private http: HttpClient) {}

  // POST /api/password/forgot  { email }
  forgot(email: string): Observable<{ message: string }> {
    return this.http
      .post<{ message: string }>(AUTH_ROUTES.forgot(), { email }, {
        headers: { 'Content-Type': 'application/json' }
      })
      .pipe(catchError(err => throwError(() => err)));
  }

  // POST /api/password/reset  { token, password }
  reset(token: string, password: string): Observable<{ message: string }> {
    return this.http
      .post<{ message: string }>(AUTH_ROUTES.reset(), { token, password }, {
        headers: { 'Content-Type': 'application/json' }
      })
      .pipe(catchError(err => throwError(() => err)));
  }
}