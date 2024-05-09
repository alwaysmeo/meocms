import{b as I,r as xt,s as m,g as T,u as Ot,v as q,N as rt,a as p,w as Y,x as C,y as A,e as Pt,q as Kt}from"./vue_shared.BOb35T8V.js";/**
* @vue/reactivity v3.4.26
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/let l;class At{constructor(e=!1){this.detached=e,this._active=!0,this.effects=[],this.cleanups=[],this.parent=l,!e&&l&&(this.index=(l.scopes||(l.scopes=[])).push(this)-1)}get active(){return this._active}run(e){if(this._active){const s=l;try{return l=this,e()}finally{l=s}}}on(){l=this}off(){l=this.parent}stop(e){if(this._active){let s,n;for(s=0,n=this.effects.length;s<n;s++)this.effects[s].stop();for(s=0,n=this.cleanups.length;s<n;s++)this.cleanups[s]();if(this.scopes)for(s=0,n=this.scopes.length;s<n;s++)this.scopes[s].stop(!0);if(!this.detached&&this.parent&&!e){const r=this.parent.scopes.pop();r&&r!==this&&(this.parent.scopes[this.index]=r,r.index=this.index)}this.parent=void 0,this._active=!1}}}function ce(t){return new At(t)}function it(t,e=l){e&&e.active&&e.effects.push(t)}function oe(){return l}function le(t){l&&l.cleanups.push(t)}let w;class V{constructor(e,s,n,r){this.fn=e,this.trigger=s,this.scheduler=n,this.active=!0,this.deps=[],this._dirtyLevel=4,this._trackId=0,this._runnings=0,this._shouldSchedule=!1,this._depsLength=0,it(this,r)}get dirty(){if(this._dirtyLevel===2||this._dirtyLevel===3){this._dirtyLevel=1,ot();for(let e=0;e<this._depsLength;e++){const s=this.deps[e];if(s.computed&&(Ct(s.computed),this._dirtyLevel>=4))break}this._dirtyLevel===1&&(this._dirtyLevel=0),lt()}return this._dirtyLevel>=4}set dirty(e){this._dirtyLevel=e?4:0}run(){if(this._dirtyLevel=0,!this.active)return this.fn();let e=g,s=w;try{return g=!0,w=this,this._runnings++,Z(this),this.fn()}finally{$(this),this._runnings--,w=s,g=e}}stop(){this.active&&(Z(this),$(this),this.onStop&&this.onStop(),this.active=!1)}}function Ct(t){return t.value}function Z(t){t._trackId++,t._depsLength=0}function $(t){if(t.deps.length>t._depsLength){for(let e=t._depsLength;e<t.deps.length;e++)at(t.deps[e],t);t.deps.length=t._depsLength}}function at(t,e){const s=t.get(e);s!==void 0&&e._trackId!==s&&(t.delete(e),t.size===0&&t.cleanup())}function ue(t,e){t.effect instanceof V&&(t=t.effect.fn);const s=new V(t,rt,()=>{s.dirty&&s.run()});e&&(Pt(s,e),e.scope&&it(s,e.scope)),(!e||!e.lazy)&&s.run();const n=s.run.bind(s);return n.effect=s,n}function fe(t){t.effect.stop()}let g=!0,W=0;const ct=[];function ot(){ct.push(g),g=!1}function lt(){const t=ct.pop();g=t===void 0?!0:t}function B(){W++}function U(){for(W--;!W&&F.length;)F.shift()()}function ut(t,e,s){if(e.get(t)!==t._trackId){e.set(t,t._trackId);const n=t.deps[t._depsLength];n!==e?(n&&at(n,t),t.deps[t._depsLength++]=e):t._depsLength++}}const F=[];function ft(t,e,s){B();for(const n of t.keys()){let r;n._dirtyLevel<e&&(r??(r=t.get(n)===n._trackId))&&(n._shouldSchedule||(n._shouldSchedule=n._dirtyLevel===0),n._dirtyLevel=e),n._shouldSchedule&&(r??(r=t.get(n)===n._trackId))&&(n.trigger(),(!n._runnings||n.allowRecurse)&&n._dirtyLevel!==2&&(n._shouldSchedule=!1,n.scheduler&&F.push(n.scheduler)))}U()}const ht=(t,e)=>{const s=new Map;return s.cleanup=t,s.computed=e,s},H=new WeakMap,R=Symbol(""),G=Symbol("");function u(t,e,s){if(g&&w){let n=H.get(t);n||H.set(t,n=new Map);let r=n.get(s);r||n.set(s,r=ht(()=>n.delete(s))),ut(w,r)}}function v(t,e,s,n,r,i){const a=H.get(t);if(!a)return;let c=[];if(e==="clear")c=[...a.values()];else if(s==="length"&&p(t)){const f=Number(n);a.forEach((y,h)=>{(h==="length"||!I(h)&&h>=f)&&c.push(y)})}else switch(s!==void 0&&c.push(a.get(s)),e){case"add":p(t)?Y(s)&&c.push(a.get("length")):(c.push(a.get(R)),A(t)&&c.push(a.get(G)));break;case"delete":p(t)||(c.push(a.get(R)),A(t)&&c.push(a.get(G)));break;case"set":A(t)&&c.push(a.get(R));break}B();for(const f of c)f&&ft(f,4);U()}function Ht(t,e){const s=H.get(t);return s&&s.get(e)}const jt=Kt("__proto__,__v_isRef,__isVue"),dt=new Set(Object.getOwnPropertyNames(Symbol).filter(t=>t!=="arguments"&&t!=="caller").map(t=>Symbol[t]).filter(I)),k=zt();function zt(){const t={};return["includes","indexOf","lastIndexOf"].forEach(e=>{t[e]=function(...s){const n=o(this);for(let i=0,a=this.length;i<a;i++)u(n,"get",i+"");const r=n[e](...s);return r===-1||r===!1?n[e](...s.map(o)):r}}),["push","pop","shift","unshift","splice"].forEach(e=>{t[e]=function(...s){ot(),B();const n=o(this)[e].apply(this,s);return U(),lt(),n}}),t}function Dt(t){I(t)||(t=String(t));const e=o(this);return u(e,"has",t),e.hasOwnProperty(t)}class _t{constructor(e=!1,s=!1){this._isReadonly=e,this._isShallow=s}get(e,s,n){const r=this._isReadonly,i=this._isShallow;if(s==="__v_isReactive")return!r;if(s==="__v_isReadonly")return r;if(s==="__v_isShallow")return i;if(s==="__v_raw")return n===(r?i?Et:Rt:i?wt:vt).get(e)||Object.getPrototypeOf(e)===Object.getPrototypeOf(n)?e:void 0;const a=p(e);if(!r){if(a&&C(k,s))return Reflect.get(k,s,n);if(s==="hasOwnProperty")return Dt}const c=Reflect.get(e,s,n);return(I(s)?dt.has(s):jt(s))||(r||u(e,"get",s),i)?c:d(c)?a&&Y(s)?c:c.value:T(c)?r?yt(c):mt(c):c}}class pt extends _t{constructor(e=!1){super(!1,e)}set(e,s,n,r){let i=e[s];if(!this._isShallow){const f=S(i);if(!bt(n)&&!S(n)&&(i=o(i),n=o(n)),!p(e)&&d(i)&&!d(n))return f?!1:(i.value=n,!0)}const a=p(e)&&Y(s)?Number(s)<e.length:C(e,s),c=Reflect.set(e,s,n,r);return e===o(r)&&(a?m(n,i)&&v(e,"set",s,n):v(e,"add",s,n)),c}deleteProperty(e,s){const n=C(e,s);e[s];const r=Reflect.deleteProperty(e,s);return r&&n&&v(e,"delete",s,void 0),r}has(e,s){const n=Reflect.has(e,s);return(!I(s)||!dt.has(s))&&u(e,"has",s),n}ownKeys(e){return u(e,"iterate",p(e)?"length":R),Reflect.ownKeys(e)}}class gt extends _t{constructor(e=!1){super(!0,e)}set(e,s){return!0}deleteProperty(e,s){return!0}}const Nt=new pt,Vt=new gt,Wt=new pt(!0),Ft=new gt(!0),J=t=>t,j=t=>Reflect.getPrototypeOf(t);function M(t,e,s=!1,n=!1){t=t.__v_raw;const r=o(t),i=o(e);s||(m(e,i)&&u(r,"get",e),u(r,"get",i));const{has:a}=j(r),c=n?J:s?Q:b;if(a.call(r,e))return c(t.get(e));if(a.call(r,i))return c(t.get(i));t!==r&&t.get(e)}function x(t,e=!1){const s=this.__v_raw,n=o(s),r=o(t);return e||(m(t,r)&&u(n,"has",t),u(n,"has",r)),t===r?s.has(t):s.has(t)||s.has(r)}function O(t,e=!1){return t=t.__v_raw,!e&&u(o(t),"iterate",R),Reflect.get(t,"size",t)}function tt(t){t=o(t);const e=o(this);return j(e).has.call(e,t)||(e.add(t),v(e,"add",t,t)),this}function et(t,e){e=o(e);const s=o(this),{has:n,get:r}=j(s);let i=n.call(s,t);i||(t=o(t),i=n.call(s,t));const a=r.call(s,t);return s.set(t,e),i?m(e,a)&&v(s,"set",t,e):v(s,"add",t,e),this}function st(t){const e=o(this),{has:s,get:n}=j(e);let r=s.call(e,t);r||(t=o(t),r=s.call(e,t)),n&&n.call(e,t);const i=e.delete(t);return r&&v(e,"delete",t,void 0),i}function nt(){const t=o(this),e=t.size!==0,s=t.clear();return e&&v(t,"clear",void 0,void 0),s}function P(t,e){return function(n,r){const i=this,a=i.__v_raw,c=o(a),f=e?J:t?Q:b;return!t&&u(c,"iterate",R),a.forEach((y,h)=>n.call(r,f(y),f(h),i))}}function K(t,e,s){return function(...n){const r=this.__v_raw,i=o(r),a=A(i),c=t==="entries"||t===Symbol.iterator&&a,f=t==="keys"&&a,y=r[t](...n),h=s?J:e?Q:b;return!e&&u(i,"iterate",f?G:R),{next(){const{value:L,done:N}=y.next();return N?{value:L,done:N}:{value:c?[h(L[0]),h(L[1])]:h(L),done:N}},[Symbol.iterator](){return this}}}}function _(t){return function(...e){return t==="delete"?!1:t==="clear"?void 0:this}}function Gt(){const t={get(i){return M(this,i)},get size(){return O(this)},has:x,add:tt,set:et,delete:st,clear:nt,forEach:P(!1,!1)},e={get(i){return M(this,i,!1,!0)},get size(){return O(this)},has:x,add:tt,set:et,delete:st,clear:nt,forEach:P(!1,!0)},s={get(i){return M(this,i,!0)},get size(){return O(this,!0)},has(i){return x.call(this,i,!0)},add:_("add"),set:_("set"),delete:_("delete"),clear:_("clear"),forEach:P(!0,!1)},n={get(i){return M(this,i,!0,!0)},get size(){return O(this,!0)},has(i){return x.call(this,i,!0)},add:_("add"),set:_("set"),delete:_("delete"),clear:_("clear"),forEach:P(!0,!0)};return["keys","values","entries",Symbol.iterator].forEach(i=>{t[i]=K(i,!1,!1),s[i]=K(i,!0,!1),e[i]=K(i,!1,!0),n[i]=K(i,!0,!0)}),[t,s,e,n]}const[qt,Yt,Bt,Ut]=Gt();function z(t,e){const s=e?t?Ut:Bt:t?Yt:qt;return(n,r,i)=>r==="__v_isReactive"?!t:r==="__v_isReadonly"?t:r==="__v_raw"?n:Reflect.get(C(s,r)&&r in n?s:n,r,i)}const Jt={get:z(!1,!1)},Qt={get:z(!1,!0)},Xt={get:z(!0,!1)},Zt={get:z(!0,!0)},vt=new WeakMap,wt=new WeakMap,Rt=new WeakMap,Et=new WeakMap;function $t(t){switch(t){case"Object":case"Array":return 1;case"Map":case"Set":case"WeakMap":case"WeakSet":return 2;default:return 0}}function kt(t){return t.__v_skip||!Object.isExtensible(t)?0:$t(Ot(t))}function mt(t){return S(t)?t:D(t,!1,Nt,Jt,vt)}function he(t){return D(t,!1,Wt,Qt,wt)}function yt(t){return D(t,!0,Vt,Xt,Rt)}function de(t){return D(t,!0,Ft,Zt,Et)}function D(t,e,s,n,r){if(!T(t)||t.__v_raw&&!(e&&t.__v_isReactive))return t;const i=r.get(t);if(i)return i;const a=kt(t);if(a===0)return t;const c=new Proxy(t,a===2?n:s);return r.set(t,c),c}function St(t){return S(t)?St(t.__v_raw):!!(t&&t.__v_isReactive)}function S(t){return!!(t&&t.__v_isReadonly)}function bt(t){return!!(t&&t.__v_isShallow)}function _e(t){return t?!!t.__v_raw:!1}function o(t){const e=t&&t.__v_raw;return e?o(e):t}function pe(t){return Object.isExtensible(t)&&xt(t,"__v_skip",!0),t}const b=t=>T(t)?mt(t):t,Q=t=>T(t)?yt(t):t;class It{constructor(e,s,n,r){this.getter=e,this._setter=s,this.dep=void 0,this.__v_isRef=!0,this.__v_isReadonly=!1,this.effect=new V(()=>e(this._value),()=>E(this,this.effect._dirtyLevel===2?2:3)),this.effect.computed=this,this.effect.active=this._cacheable=!r,this.__v_isReadonly=n}get value(){const e=o(this);return(!e._cacheable||e.effect.dirty)&&m(e._value,e._value=e.effect.run())&&E(e,4),X(e),e.effect._dirtyLevel>=2&&E(e,2),e._value}set value(e){this._setter(e)}get _dirty(){return this.effect.dirty}set _dirty(e){this.effect.dirty=e}}function ge(t,e,s=!1){let n,r;const i=q(t);return i?(n=t,r=rt):(n=t.get,r=t.set),new It(n,r,i||!r,s)}function X(t){var e;g&&w&&(t=o(t),ut(w,(e=t.dep)!=null?e:t.dep=ht(()=>t.dep=void 0,t instanceof It?t:void 0)))}function E(t,e=4,s){t=o(t);const n=t.dep;n&&ft(n,e)}function d(t){return!!(t&&t.__v_isRef===!0)}function te(t){return Tt(t,!1)}function ve(t){return Tt(t,!0)}function Tt(t,e){return d(t)?t:new ee(t,e)}class ee{constructor(e,s){this.__v_isShallow=s,this.dep=void 0,this.__v_isRef=!0,this._rawValue=s?e:o(e),this._value=s?e:b(e)}get value(){return X(this),this._value}set value(e){const s=this.__v_isShallow||bt(e)||S(e);e=s?e:o(e),m(e,this._rawValue)&&(this._rawValue=e,this._value=s?e:b(e),E(this,4))}}function we(t){E(t,4)}function Lt(t){return d(t)?t.value:t}function Re(t){return q(t)?t():Lt(t)}const se={get:(t,e,s)=>Lt(Reflect.get(t,e,s)),set:(t,e,s,n)=>{const r=t[e];return d(r)&&!d(s)?(r.value=s,!0):Reflect.set(t,e,s,n)}};function Ee(t){return St(t)?t:new Proxy(t,se)}class ne{constructor(e){this.dep=void 0,this.__v_isRef=!0;const{get:s,set:n}=e(()=>X(this),()=>E(this));this._get=s,this._set=n}get value(){return this._get()}set value(e){this._set(e)}}function me(t){return new ne(t)}function ye(t){const e=p(t)?new Array(t.length):{};for(const s in t)e[s]=Mt(t,s);return e}class re{constructor(e,s,n){this._object=e,this._key=s,this._defaultValue=n,this.__v_isRef=!0}get value(){const e=this._object[this._key];return e===void 0?this._defaultValue:e}set value(e){this._object[this._key]=e}get dep(){return Ht(o(this._object),this._key)}}class ie{constructor(e){this._getter=e,this.__v_isRef=!0,this.__v_isReadonly=!0}get value(){return this._getter()}}function Se(t,e,s){return d(t)?t:q(t)?new ie(t):T(t)&&arguments.length>1?Mt(t,e,s):te(t)}function Mt(t,e,s){const n=t[e];return d(n)?n:new re(t,e,s)}const be={GET:"get",HAS:"has",ITERATE:"iterate"},Ie={SET:"set",ADD:"add",DELETE:"delete",CLEAR:"clear"};export{yt as A,fe as B,Se as C,ye as D,At as E,Re as F,V as R,be as T,mt as a,d as b,bt as c,St as d,ce as e,ge as f,lt as g,oe as h,_e as i,Ee as j,o as k,u as l,pe as m,he as n,v as o,ot as p,de as q,te as r,ve as s,we as t,Lt as u,me as v,Ie as w,ue as x,S as y,le as z};
