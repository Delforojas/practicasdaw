import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, catchError, Observable, of, switchMap, tap, throwError } from 'rxjs';
import { LoginDTO } from '../interfaces/LoginDTO';
import { User } from '../interfaces/User';
import { AUTH_ROUTES } from '../routes/auth-routes';


@Injectable({
 providedIn: 'root'
})
export class AuthService {
 private userSubject = new BehaviorSubject<User | null>(null);


 constructor(private http: HttpClient) { }


 /*register(data: RegisterDTO) {
  return this.http
    .post<{ id: number }>(AUTH_ROUTES.register(), data, {
      headers: { 'Content-Type': 'application/json' }
    })
  
}*/
register(formData: FormData) {
  return this.http.post<{ message: string }>(AUTH_ROUTES.register(), formData);
}

 login(data: LoginDTO) {
   return this.http.post(AUTH_ROUTES.login(), data, { withCredentials: true }).pipe(
     switchMap(() => this.http.get<User>(AUTH_ROUTES.me(), { withCredentials: true })),
     tap(user => this.userSubject.next(user)),
   );
 }




  loadUser(): Observable<User | null> {
   return this.http.get<User>(AUTH_ROUTES.me(), { withCredentials: true }).pipe(
     tap(user => this.userSubject.next(user)),
     catchError(err => {
       console.warn('Error loading user:', err);
       this.userSubject.next(null);
       return of(null);
     })
   );
 }




 logout() {
   return this.http.post(AUTH_ROUTES.logout(), {}, { withCredentials: true }).pipe(
     tap(() => this.userSubject.next(null))
   );
 }


 get currentUser$(): Observable<User | null> {
   return this.userSubject.asObservable();
 }



  getMe(): Observable<User> {
    return this.http.get<User>(AUTH_ROUTES.me(), { withCredentials: true });
  }



}