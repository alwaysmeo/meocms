import{r as x,b as L,n as H,c as f,a as y,g as R,d,T as K,t as w,f as $,h as A,e as P,i as G,j as m,k as S,l as j}from"./vue_compiler-core.CC2by-8O.js";import{e as M,l as B,m as J,n as W,o as Y,p as q,d as z,q as u}from"./vue_shared.BOb35T8V.js";/**
* @vue/compiler-dom v3.4.26
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/const v=Symbol(""),O=Symbol(""),C=Symbol(""),b=Symbol(""),h=Symbol(""),_=Symbol(""),N=Symbol(""),k=Symbol(""),I=Symbol(""),D=Symbol("");x({[v]:"vModelRadio",[O]:"vModelCheckbox",[C]:"vModelText",[b]:"vModelSelect",[h]:"vModelDynamic",[_]:"withModifiers",[N]:"withKeys",[k]:"vShow",[I]:"Transition",[D]:"TransitionGroup"});let p;function U(e,t=!1){return p||(p=document.createElement("div")),t?(p.innerHTML=`<div foo="${e.replace(/"/g,"&quot;")}">`,p.children[0].getAttribute("foo")):(p.innerHTML=e,p.textContent)}const X={parseMode:"html",isVoidTag:B,isNativeTag:e=>J(e)||W(e)||Y(e),isPreTag:e=>e==="pre",decodeEntities:U,isBuiltInComponent:e=>{if(e==="Transition"||e==="transition")return I;if(e==="TransitionGroup"||e==="transition-group")return D},getNamespace(e,t,o){let s=t?t.ns:o;if(t&&s===2)if(t.tag==="annotation-xml"){if(e==="svg")return 1;t.props.some(r=>r.type===6&&r.name==="encoding"&&r.value!=null&&(r.value.content==="text/html"||r.value.content==="application/xhtml+xml"))&&(s=0)}else/^m(?:[ions]|text)$/.test(t.tag)&&e!=="mglyph"&&e!=="malignmark"&&(s=0);else t&&s===1&&(t.tag==="foreignObject"||t.tag==="desc"||t.tag==="title")&&(s=0);if(s===0){if(e==="svg")return 1;if(e==="math")return 2}return s}},F=e=>{e.type===1&&e.props.forEach((t,o)=>{t.type===6&&t.name==="style"&&t.value&&(e.props[o]={type:7,name:"bind",arg:f("style",!0,t.loc),exp:Q(t.value.content,t.loc),modifiers:[],loc:t.loc})})},Q=(e,t)=>{const o=q(e);return f(JSON.stringify(o),!1,t,3)};function c(e,t){return G(e,t)}const Z=(e,t,o)=>{const{exp:s,loc:r}=e;return s||o.onError(c(53,r)),t.children.length&&(o.onError(c(54,r)),t.children.length=0),{props:[y(f("innerHTML",!0,r),s||f("",!0))]}},ee=(e,t,o)=>{const{exp:s,loc:r}=e;return s||o.onError(c(55,r)),t.children.length&&(o.onError(c(56,r)),t.children.length=0),{props:[y(f("textContent",!0),s?R(s,o)>0?s:d(o.helperString(K),[s],r):f("",!0))]}},te=(e,t,o)=>{const s=w(e,t,o);if(!s.props.length||t.tagType===1)return s;e.arg&&o.onError(c(58,e.arg.loc));const{tag:r}=t,i=o.isCustomElement(r);if(r==="input"||r==="textarea"||r==="select"||i){let l=C,a=!1;if(r==="input"||i){const n=$(t,"type");if(n){if(n.type===7)l=h;else if(n.value)switch(n.value.content){case"radio":l=v;break;case"checkbox":l=O;break;case"file":a=!0,o.onError(c(59,e.loc));break}}else A(t)&&(l=h)}else r==="select"&&(l=b);a||(s.needRuntime=o.helper(l))}else o.onError(c(57,e.loc));return s.props=s.props.filter(l=>!(l.key.type===4&&l.key.content==="modelValue")),s},oe=u("passive,once,capture"),re=u("stop,prevent,self,ctrl,shift,alt,meta,exact,middle"),se=u("left,right"),V=u("onkeyup,onkeydown,onkeypress",!0),ne=(e,t,o,s)=>{const r=[],i=[],l=[];for(let a=0;a<t.length;a++){const n=t[a];n==="native"&&j("COMPILER_V_ON_NATIVE",o)||oe(n)?l.push(n):se(n)?m(e)?V(e.content)?r.push(n):i.push(n):(r.push(n),i.push(n)):re(n)?i.push(n):r.push(n)}return{keyModifiers:r,nonKeyModifiers:i,eventOptionModifiers:l}},E=(e,t)=>m(e)&&e.content.toLowerCase()==="onclick"?f(t,!0):e.type!==4?S(["(",e,`) === "onClick" ? "${t}" : (`,e,")"]):e,ie=(e,t,o)=>P(e,t,o,s=>{const{modifiers:r}=e;if(!r.length)return s;let{key:i,value:l}=s.props[0];const{keyModifiers:a,nonKeyModifiers:n,eventOptionModifiers:g}=ne(i,r,o,e.loc);if(n.includes("right")&&(i=E(i,"onContextmenu")),n.includes("middle")&&(i=E(i,"onMouseup")),n.length&&(l=d(o.helper(_),[l,JSON.stringify(n)])),a.length&&(!m(i)||V(i.content))&&(l=d(o.helper(N),[l,JSON.stringify(a)])),g.length){const T=g.map(z).join("");i=m(i)?f(`${i.content}${T}`,!0):S(["(",i,`) + "${T}"`])}return{props:[y(i,l)]}}),le=(e,t,o)=>{const{exp:s,loc:r}=e;return s||o.onError(c(61,r)),{props:[],needRuntime:o.helper(k)}},ae=(e,t)=>{e.type===1&&e.tagType===0&&(e.tag==="script"||e.tag==="style")&&t.removeNode()},ce=[F],fe={cloak:H,html:Z,text:ee,model:te,on:ie,show:le};function ue(e,t={}){return L(e,M({},X,t,{nodeTransforms:[ae,...ce,...t.nodeTransforms||[]],directiveTransforms:M({},fe,t.directiveTransforms||{}),transformHoist:null}))}export{ue as c};
