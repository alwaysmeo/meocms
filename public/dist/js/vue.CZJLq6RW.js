import{b as d}from"./vue_runtime-dom.BgRfd05v.js";import{c as a}from"./vue_compiler-dom.D-ER5MIp.js";import{i as E,N as u,e as g,E as m}from"./vue_shared.BOb35T8V.js";import{r as h}from"./vue_runtime-core.Dp8nAaai.js";/**
* vue v3.4.26
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/const f=new WeakMap;function C(e){let n=f.get(e??m);return n||(n=Object.create(null),f.set(e??m,n)),n}function T(e,n){if(!E(e))if(e.nodeType)e=e.innerHTML;else return u;const c=e,t=C(n),i=t[c];if(i)return i;if(e[0]==="#"){const r=document.querySelector(e);e=r?r.innerHTML:""}const o=g({hoistStatic:!0,onError:void 0,onWarn:u},n);!o.isCustomElement&&typeof customElements<"u"&&(o.isCustomElement=r=>!!customElements.get(r));const{code:l}=a(e,o),s=new Function("Vue",l)(d);return s._rc=!0,t[c]=s}h(T);
