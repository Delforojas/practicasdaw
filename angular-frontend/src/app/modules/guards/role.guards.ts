import { Injectable } from '@angular/core';
import {
 CanActivate,ActivatedRouteSnapshot,
 Router,
} from '@angular/router';
import { Observable, of } from 'rxjs';
import { catchError, map, switchMap, take } from 'rxjs/operators';

import { NavigationService } from '../../shared/service/navigation.service';
import { AuthService } from '../../shared/service/auth.service';



@Injectable({
 providedIn: 'root',
})
export class RoleGuard implements CanActivate {
 constructor(private authService: AuthService, private router: Router ,private navigation :NavigationService) { }


 canActivate(route: ActivatedRouteSnapshot): Observable<boolean> {
   const expectedRole = route.data['expectedRole'];


   return this.authService.loadUser().pipe(
     map(user => {
       return !!user && this.checkRole(user.role, expectedRole);
     }),
     catchError(() => {
       this.navigation.goTo('/not-found');
       return of(false);
     })
   );
 }


 private checkRole(actualRole: string, expectedRole: string): boolean {
   if (actualRole === expectedRole) {
     return true;
   }
   this.navigation.goTo('/not-found');
   return false;
 }


}